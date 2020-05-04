<?php

namespace App\Controller;

use App\Entity\PasswordForgot;
use App\Entity\PasswordReset;
use App\Entity\PasswordUpdate;
use App\Entity\User;
use App\Form\AccountType;
use App\Form\PasswordForgotType;
use App\Form\PasswordResetType;
use App\Form\PasswordUpdateType;
use App\Form\RegistrationType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AccountController extends AbstractController
{

    /**
     * Login function
     *
     * @Route("/login", name="account_login")
     *
     * @param AuthenticationUtils $utils
     *
     * @return Response
     */
    public function login(AuthenticationUtils $utils): Response
    {
        $error = $utils->getLastAuthenticationError();
        $username = $utils->getLastUsername();
        return $this->render('account/login.html.twig', [
            'username' => $username,
            'error' => $error ? $error->getMessage() : null,
        ]);
    }

    /**
     * Logout function
     *
     * @Route("/logout", name="account_logout")
     *
     * @return void
     */
    public function logout(): void
    {}

    /**
     * Display register form
     *
     * @Route("/register", name="account_register")
     *
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @param UserPasswordEncoderInterface $encoder
     *
     * @return Response
     *
     * @throws Exception
     */
    public function register(Request $request, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $password = $encoder->encodePassword($user, $user->getPassword());
            $createdAt = new \DateTime();
            $confirmation_token = md5(random_bytes(60));
            $user->setPassword($password)
                ->setAvatar('default-avatar.jpg')
                ->setCreatedAt($createdAt)
                ->setConfirmed(false)
                ->setRole('ROLE_USER')
                ->setConfirmationToken($confirmation_token);
            $manager->persist($user);
            $manager->flush();
            $subject = 'Confirmation de compte';
            $content = $this->renderView('emails/registration.html.twig', [
                'username' => $user->getUsername(),
                'id' => $user->getId(),
                'token' => $user->getConfirmationToken(),
                'adress' => $request->server->get('SERVER_NAME')
            ]);
            $headers = 'From: "Snowtricks"<webdev@jeandescorps.fr>' . "\n";
            $headers .= 'Reply-To: jean.webdev@gmail.com' . "\n";
            $headers .= 'Content-Type: text/html; charset="iso-8859-1"' . "\n";
            $headers .= 'Content-Transfer-Encoding: 8bit';
            mail($user->getEmail(), $subject, $content, $headers);
            $this->addFlash('success', 'Votre compte a bien été crée, un email vous a été envoyé pour le confirmer.');
            return $this->redirectToRoute('account_register');
        }
        return $this->render('account/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Email confirmation
     *
     * @Route("/confirm", name="account_confirm")
     *
     * @param Request $request
     * @param UserRepository $repo
     * @param EntityManagerInterface $manager
     *
     * @return Response
     *
     * @throws Exception
     */
    public function confirm(Request $request, UserRepository $repo, EntityManagerInterface $manager): ?Response
    {
        if ($request->query->get('id')) {
            $id = $request->query->get('id');
        } else {
            throw new Exception('Veuillez cliquer sur le lien fournit dans l\'email qui vous a été envoyé pour vous valider !');
        }
        if ($request->query->get('token')) {
            $token = $request->query->get('token');
        } else {
            throw new Exception('Veuillez cliquer sur le lien fournit dans l\'email qui vous a été envoyé pour vous valider !');
        }
        $user = $repo->findOneBy(array('id' => $id));
        if ($user->getId()) {
            if ($user->getConfirmationToken() === $token) {
                $user->setConfirmationToken(null)
                    ->setConfirmed(true);
                $manager->flush();
                $this->addFlash('success', 'Votre compte est validé ! Connectez-vous !');
                return $this->redirectToRoute('account_login');
            } else {
                throw new Exception('Veuillez cliquer sur le lien fournit dans l\'email qui vous a été envoyé pour vous valider !');
            }
        } else {
            throw new Exception('Veuillez cliquer sur le lien fournit dans l\'email qui vous a été envoyé pour vous valider !');
        }
    }

    /**
     * Display profile and update profile form
     *
     * @Route("/profile", name="account_profile")
     *
     * @param Request $request
     * @param EntityManagerInterface $manager
     *
     * @return Response
     */
    public function profile(Request $request, EntityManagerInterface $manager)
    {
        $user = $this->getUser();
        $userDb = $manager->createQuery('SELECT u FROM App\Entity\User u WHERE u.id = :id')->setParameter('id', $user->getId())->getScalarResult();
        $oldAvatar = $user->getAvatar();
        $user->setAvatar(new File($this->getParameter('images_directory') . '/' . $user->getAvatar()));
        $form = $this->createForm(AccountType::class, $user, array('user' => $this->getUser()));

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if ($form->get('avatar')->getData() !== null && $form->get('avatar')->getData() !== $user->getAvatar()) {

                $newAvatar = $form->get('avatar')->getData();
                $avatarName = $this->generateUniqueFileName() . '.' . $newAvatar->guessExtension();
                $newAvatar->move(
                    $this->getParameter('images_directory'),
                    $avatarName
                );
                $user->setAvatar($avatarName);
                if ($oldAvatar !== 'default-avatar.jpg') {
                    unlink($this->getParameter('images_directory') . '/' . $oldAvatar);
                }
            } else {
                $user->setAvatar($oldAvatar);
            }

            $manager->flush();
            $this->addFlash('success', 'Votre compte a été mis à jour.');
            return $this->redirectToRoute('account_profile');
        }
        return $this->render('account/profile.html.twig', [
            'form' => $form->createView(),
            'user' => $userDb[0],
        ]);
    }

    /**
     * Update password
     *
     * @Route("profile/update-password", name="account_password")
     *
     * @param Request $request
     * @param UserPasswordEncoderInterface $encoder
     * @param EntityManagerInterface $manager
     *
     * @return Response
     */
    public function updatePassword(Request $request, UserPasswordEncoderInterface $encoder, EntityManagerInterface $manager)
    {
        $passwordUpdate = new PasswordUpdate();
        $user = $this->getUser();
        $form = $this->createForm(PasswordUpdateType::class, $passwordUpdate);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $newPassword = $passwordUpdate->getNewPassword();
            $password = $encoder->encodePassword($user, $newPassword);
            $user->setPassword($password);
            $manager->persist($user);
            $manager->flush();
            $this->addFlash('success', 'Votre mot de passe a été mis à jour.');
            return $this->redirectToRoute('account_password');
        }
        return $this->render('account/update-password.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Forgot password
     *
     * @Route("/forgot-password", name="account_forgot")
     *
     * @param Request $request
     * @param UserRepository $repo
     * @param EntityManagerInterface $manager
     *
     * @return Response
     */
    public function forgotPassword(Request $request, UserRepository $repo, EntityManagerInterface $manager)
    {
        $passwordForgot = new PasswordForgot();
        $user = new User();
        $form = $this->createForm(PasswordForgotType::class, $passwordForgot);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($repo->findOneBy(array('username' => $passwordForgot->getUsername()))) {
                $user = $repo->findOneBy(array('username' => $passwordForgot->getUsername()));
                $confirmation_token = md5(random_bytes(60));
                $user->setConfirmationToken($confirmation_token);
                $manager->flush();
                $subject = 'Réinitialisation du mot de passe';
                $content = $this->renderView('emails/forgot-password.html.twig', [
                    'username' => $user->getUsername(),
                    'id' => $user->getId(),
                    'token' => $user->getConfirmationToken(),
                    'adress' => $request->server->get('SERVER_NAME'),
                ]
                );
                $headers = 'From: "Snowtricks"<webdev@jeandescorps.fr>' . "\n";
                $headers .= 'Reply-To: jean.webdev@gmail.com' . "\n";
                $headers .= 'Content-Type: text/html; charset="iso-8859-1"' . "\n";
                $headers .= 'Content-Transfer-Encoding: 8bit';
                mail($user->getEmail(), $subject, $content, $headers);
                $this->addFlash('success', 'Un email vient de vous être envoyé pour réinitialiser votre mot de passe !');
                return $this->redirectToRoute('account_forgot');
            }

            $this->addFlash('danger', 'Cet utilisateur n\'existe pas.');
            return $this->redirectToRoute('account_forgot');
        }
        return $this->render('account/forgot-password.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Reset password (forgot)
     *
     * @Route("/reset-password", name="account_reset")
     *
     * @param Request $request
     * @param UserRepository $repo
     * @param EntityManagerInterface $manager
     * @param UserPasswordEncoderInterface $encoder
     *
     * @return Response
     */
    public function resetPassword(Request $request, UserRepository $repo, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder)
    {
        if (!$request->query->get('id')) {
            throw new Exception('Veuillez cliquer sur le lien fournit dans l\'email qui vous a été envoyé pour réinitialiser votre mot de passe !');
        }
        if (!$request->query->get('token')) {
            throw new Exception('Veuillez cliquer sur le lien fournit dans l\'email qui vous a été envoyé pour réinitialiser votre mot de passe !');
        }

        $token = $request->query->get('token');
        $id = $request->query->get('id');

        $passwordReset = new PasswordReset();
        $user = $repo->findOneBy(['id' => $id]);

        $form = $this->createForm(PasswordResetType::class, $passwordReset);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if ($user->getId()) {
                if ($user->getEmail() === $passwordReset->getEmail()) {
                    if ($user->getConfirmationToken() === $token) {
                        $newPassword = $passwordReset->getNewPassword();
                        $password = $encoder->encodePassword($user, $newPassword);
                        $user->setConfirmationToken(null)
                            ->setPassword($password);
                        $manager->persist($user);
                        $manager->flush();
                        $this->addFlash('success', 'Votre mot de passe a été mis à jour ! Connectez-vous !');
                        return $this->redirectToRoute('account_login');
                    }

                    throw new Exception('Veuillez cliquer sur le lien fournit dans l\'email qui vous a été envoyé pour réinitialiser votre mot de passe !');
                }

                $this->addFlash('success', 'Cette adresse email n\'est pas celle associée à votre compte !');
                return $this->redirectToRoute('account_login');
            }

            throw new Exception('Veuillez cliquer sur le lien fournit dans l\'email qui vous a été envoyé pour réinitialiser votre mot de passe !');
        }

        return $this->render('account/reset-password.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Generate unique file name
     *
     * @return string
     */
    private function generateUniqueFileName()
    {
        return md5(uniqid());
    }
}

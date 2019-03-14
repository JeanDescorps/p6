<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\AccountType;
use App\Form\RegistrationType;
use App\Repository\UserRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
    public function login(AuthenticationUtils $utils)
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
    public function logout()
    {}

    /**
     * Display register form
     *
     * @Route("/register", name="account_register")
     *
     * @param Request $request
     * @param ObjectManager $manager
     * @param UserPasswordEncoderInterface $encoder
     * @param \Swift_Mailer $mailer
     *
     * @return Response
     */
    public function register(Request $request, ObjectManager $manager, UserPasswordEncoderInterface $encoder, \Swift_Mailer $mailer)
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
            $message = (new \Swift_Message('Confirmation de compte'))
                ->setFrom('jean.webdev@gmail.com')
                ->setTo($user->getEmail())
                ->setBody(
                    $this->renderView('emails/registration.html.twig', [
                        'username' => $user->getUsername(),
                        'id' => $user->getId(),
                        'token' => $user->getConfirmationToken(),
                        'adress' => $_SERVER['SERVER_NAME'] . ':8000',
                    ]
                    ),
                    'text/html'
                );
            $mailer->send($message);
            $success = 'Votre compte a bien été crée, un email vous a été envoyé pour le confirmer.';
        }
        return $this->render('account/register.html.twig', [
            'form' => $form->createView(),
            'success' => $success = isset($success) ? $success : null,
        ]);
    }

    /**
     * Email confirmation
     *
     * @Route("/confirm", name="account_confirm")
     *
     * @param Request $request
     * @param UserRepository $repo
     * @param ObjectManager $manager
     *
     * @return Response
     */
    public function confirm(Request $request, UserRepository $repo, ObjectManager $manager)
    {
        $request = Request::createFromGlobals();
        $id = $request->query->get('id');
        $token = $request->query->get('token');
        $user = new User();
        $user = $repo->findOneBy(array('id' => $id));
        if ($user !== null) {
            if ($user->getConfirmationToken() === $token) {
                $user->setConfirmationToken(null)
                    ->setConfirmed(true);
                $manager->flush();
                $this->addFlash('success', 'Votre compte est validé ! Connectez-vous !');
                return $this->redirectToRoute('account_login');
            } else {
                throw new \Exception('Erreur de confirmation.');
            }
        } else {
            throw new \Exception('Cet utilisateur n\'existe pas.');
        }
    }

    /**
     * Display profile and update profile form
     *
     * @Route("/profile", name="account_profile")
     *
     * @Security("is_granted('ROLE_USER')")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function profile(Request $request, ObjectManager $manager)
    {
        $user = $this->getUser();
        $oldAvatar = $user->getAvatar();
        $form = $this->createForm(AccountType::class, $user, array('user' => $this->getUser()));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if ($form->get('avatar')->getData() !== $user->getAvatar()) {

                $newAvatar = $form->get('avatar')->getData();
                $avatarExtension = $newAvatar->guessExtension();
                $validExtension = array('jpg', 'jpeg', 'gif', 'png');

                if (in_array($avatarExtension, $validExtension)) {

                    if ($newAvatar->getSize() < 500000) {

                        $avatarName = $this->generateUniqueFileName() . '.' . $newAvatar->guessExtension();
                        $newAvatar->move(
                            $this->getParameter('images_directory'),
                            $avatarName
                        );
                        $user->setAvatar($avatarName);

                    } else {
                        $this->addFlash('danger', 'Votre avatar ne doit pas excéder 500 Ko.');
                        return $this->redirectToRoute('account_profile');
                    }
                } else {
                    $this->addFlash('danger', 'Votre avatar doit être au format jpg, jpeg, gif ou png.');
                    return $this->redirectToRoute('account_profile');
                }
            }

            $manager->flush();
            if ($oldAvatar !== 'default-avatar.jpg') {
                unlink($this->getParameter('images_directory') . '/' . $oldAvatar);
            }
            $this->addFlash('success', 'Votre compte a été mis à jour.');
            return $this->redirectToRoute('account_profile');
        }
        return $this->render('account/profile.html.twig', [
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

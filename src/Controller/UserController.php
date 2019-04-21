<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Trick;
use App\Entity\User;
use App\Service\Paging;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * Find all users
     *
     * @Route("/admin/user/{pageAc<\d+>?1}/{pageIn<\d+>?1}", name="admin_user")
     *
     * @param [type] $pageAc
     * @param [type] $pageIn
     * @param Paging $paging
     * @param ObjectManager $manager
     *
     * @return Response
     */
    public function findAll($pageAc, $pageIn, Paging $paging, ObjectManager $manager)
    {
        $pagingInactive = $paging->setEntityClass(User::class)
            ->setCurrentPage($pageIn)
            ->setLimit($this->getParameter('user_limit'))
            ->setCriteria(['confirmed' => 0]);

        $pagingActive = new Paging($manager);
        $pagingActive->setEntityClass(User::class)
            ->setCurrentPage($pageAc)
            ->setLimit($this->getParameter('user_limit'))
            ->setCriteria(['confirmed' => 1]);

        return $this->render('user/admin-user.html.twig', [
            'pagingActive' => $pagingActive,
            'pagingInactive' => $pagingInactive,
            'pageAc' => $pageAc,
            'pageIn' => $pageIn,
        ]);
    }

    /**
     * Delete user
     *
     * @Route("/admin/user/delete/{id}", name="admin_user_delete")
     *
     * @param ObjectManager $manager
     * @param User $user
     *
     * @return Response
     */
    public function delete(ObjectManager $manager, User $user)
    {
        $manager->remove($user);
        $manager->flush();
        $this->addFlash('success', 'L\'utilisateur a été supprimé.');
        return $this->redirectToRoute('admin_user');
    }

    /**
     * Display user profile with his datas
     *
     * @Route("/user/{id}/{page<\d+>?1}/{pageT<\d+>?1}", name="user_profile")
     *
     * @param User $user
     * @param [type] $page
     * @param [type] $pageT
     * @param Paging $paging
     * @param ObjectManager $manager
     *
     * @return Response
     */
    public function userProfile(User $user, $page, $pageT, Paging $paging, ObjectManager $manager)
    {
        $paging->setEntityClass(Comment::class)
            ->setCurrentPage($page)
            ->setLimit($this->getParameter('comment_user_limit'))
            ->setCriteria(['user' => $user->getId()]);

        $pagingTrick = new Paging($manager);
        $pagingTrick->setEntityClass(Trick::class)
            ->setCurrentPage($pageT)
            ->setLimit($this->getParameter('trick_user_limit'))
            ->setCriteria(['user' => $user->getId()]);

        return $this->render('user/user-profile.html.twig', [
            'user' => $user,
            'paging' => $paging,
            'pagingTrick' => $pagingTrick,
            'pageC' => $page,
            'pageT' => $pageT,
        ]);
    }

}

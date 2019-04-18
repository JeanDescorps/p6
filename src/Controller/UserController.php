<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\Paging;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/admin/user/{page<\d+>?1}/{pageIn<\d+>?1}", name="admin_user")
     */
    public function findAll($page, $pageIn, Paging $paging, ObjectManager $manager)
    {
        $pagingInactive = $paging->setEntityClass(User::class)
            ->setCurrentPage($pageIn)
            ->setLimit($this->getParameter('user_limit'))
            ->setCriteria(['confirmed' => 0]);

        $pagingActive = new Paging($manager);
        $pagingActive->setEntityClass(User::class)
            ->setCurrentPage($page)
            ->setLimit($this->getParameter('user_limit'))
            ->setCriteria(['confirmed' => 1]);

        return $this->render('user/admin-user.html.twig', [
            'pagingActive' => $pagingActive,
            'pagingInactive' => $pagingInactive,
        ]);
    }

    /**
     * @Route("/admin/user/delete/{id}", name="admin_user_delete")
     */
    public function delete(ObjectManager $manager, User $user)
    {
        $manager->remove($user);
        $manager->flush();
        $this->addFlash('success', 'L\'utilisateur a été supprimé.');
        return $this->redirectToRoute('admin_user');
    }
}

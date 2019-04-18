<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Form\CommentUpdateType;
use App\Service\Paging;
use Doctrine\Common\Persistence\ObjectManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CommentController extends AbstractController
{
    /**
     * @Route("/profile/comment/{page<\d+>?1}", name="comment_user")
     */
    public function find($page, Paging $paging)
    {
        $paging->setEntityClass(Comment::class)
            ->setCurrentPage($page)
            ->setLimit($this->getParameter('comment_limit'))
            ->setCriteria(['user' => $this->getUser()]);

        return $this->render('comment/user-comment.html.twig', [
            'paging' => $paging,
        ]);
    }

    /**
     * @Route("/admin/comment/{page<\d+>?1}", name="admin_comment")
     */
    public function findAll($page, Paging $paging)
    {
        $paging->setEntityClass(Comment::class)
            ->setCurrentPage($page)
            ->setLimit($this->getParameter('comment_limit'));

        return $this->render('comment/admin-comment.html.twig', [
            'paging' => $paging,
        ]);
    }

    /**
     * @Route("/profile/comment/edit/{id}", name="comment_update")
     * @Security(
     *      "user === comment.getUser()",
     *      message = "Ce commentaire ne vous appartient pas !"
     * )
     */
    public function update(Request $request, ObjectManager $manager, Comment $comment)
    {
        $form = $this->createForm(CommentUpdateType::class, $comment);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager->flush();
            $this->addFlash('success', 'Votre commentaire a été mis à jour.');
            return $this->redirectToRoute('comment_user');
        }
        return $this->render('comment/user-comment-update.html.twig', [
            'comment' => $comment,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/profile/comment/delete/{id}", name="comment_delete")
     * @Security(
     *      "user === comment.getUser()",
     *      message = "Ce commentaire ne vous appartient pas !"
     * )
     */
    public function delete(ObjectManager $manager, Comment $comment)
    {
        $manager->remove($comment);
        $manager->flush();
        $this->addFlash('success', 'Le commentaire a été supprimé.');
        return $this->redirectToRoute('comment_user');
    }

    /**
     * @Route("/admin/comment/edit/{id}", name="comment_admin_update")
     */
    public function updateAll(Request $request, ObjectManager $manager, Comment $comment)
    {
        $form = $this->createForm(CommentUpdateType::class, $comment);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager->flush();
            $this->addFlash('success', 'Le commentaire a été mis à jour.');
            return $this->redirectToRoute('admin_comment');
        }
        return $this->render('comment/admin-comment-update.html.twig', [
            'comment' => $comment,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/comment/delete/{id}", name="comment_admin_delete")
     */
    public function deleteAll(ObjectManager $manager, Comment $comment)
    {
        $manager->remove($comment);
        $manager->flush();
        $this->addFlash('success', 'Le commentaire a été supprimé.');
        return $this->redirectToRoute('admin_comment');
    }
}

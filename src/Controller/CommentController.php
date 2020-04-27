<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Form\CommentUpdateType;
use App\Service\Paging;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommentController extends AbstractController
{
    /**
     * Find user comment
     *
     * @Route("/profile/comment/{page<\d+>?1}", name="comment_user")
     *
     * @param [type] $page
     * @param Paging $paging
     *
     * @return Response
     */
    public function find($page, Paging $paging): Response
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
     * Find all comments
     *
     * @Route("/admin/comment/{page<\d+>?1}", name="admin_comment")
     *
     * @param [type] $page
     * @param Paging $paging
     *
     * @return Response
     */
    public function findAll($page, Paging $paging): Response
    {
        $paging->setEntityClass(Comment::class)
            ->setCurrentPage($page)
            ->setLimit($this->getParameter('comment_limit'));

        return $this->render('comment/admin-comment.html.twig', [
            'paging' => $paging,
        ]);
    }

    /**
     * Update comment for user
     *
     * @Route("/profile/comment/edit/{id}", name="comment_update")
     * @Security(
     *      "user === comment.getUser()",
     *      message = "Ce commentaire ne vous appartient pas !"
     * )
     *
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @param Comment $comment
     *
     * @return Response
     */
    public function update(Request $request, EntityManagerInterface $manager, Comment $comment): Response
    {
        $form = $this->createForm(CommentUpdateType::class, $comment);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setCreatedAt(new \DateTime());
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
     * Delete comment for user
     *
     * @Route("/profile/comment/delete/{id}", name="comment_delete")
     * @Security(
     *      "user === comment.getUser()",
     *      message = "Ce commentaire ne vous appartient pas !"
     * )
     *
     * @param EntityManagerInterface $manager
     * @param Comment $comment
     *
     * @return Response
     */
    public function delete(EntityManagerInterface $manager, Comment $comment): Response
    {
        $manager->remove($comment);
        $manager->flush();
        $this->addFlash('success', 'Le commentaire a été supprimé.');
        return $this->redirectToRoute('comment_user');
    }

    /**
     * Update comment for admin
     *
     * @Route("/admin/comment/edit/{id}", name="comment_admin_update")
     *
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @param Comment $comment
     *
     * @return Response
     */
    public function updateAll(Request $request, EntityManagerInterface $manager, Comment $comment): Response
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
     * Delete comment for admin
     *
     * @Route("/admin/comment/delete/{id}", name="comment_admin_delete")
     *
     * @param EntityManagerInterface $manager
     * @param Comment $comment
     *
     * @return Response
     */
    public function deleteAll(EntityManagerInterface $manager, Comment $comment): Response
    {
        $manager->remove($comment);
        $manager->flush();
        $this->addFlash('success', 'Le commentaire a été supprimé.');
        return $this->redirectToRoute('admin_comment');
    }
}

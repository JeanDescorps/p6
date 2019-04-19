<?php

namespace App\Controller;

use App\Repository\CommentRepository;
use App\Repository\TrickRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin_dashboard")
     */
    public function dashboard(TrickRepository $repoTrick, CommentRepository $repoComment)
    {
        $tricks = $repoTrick->findBy([], ['id' => 'DESC'], 4);
        $comments = $repoComment->findBy([], ['id' => 'DESC'], 3);
        return $this->render('admin/dashboard.html.twig', [
            'tricks' => $tricks,
            'comments' => $comments,
        ]);
    }
}

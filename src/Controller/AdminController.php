<?php

namespace App\Controller;

use App\Repository\TrickRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin_dashboard")
     */
    public function dashboard(TrickRepository $repo)
    {
        $tricks = $repo->findBy([], ['id' => 'DESC'], 4);
        return $this->render('admin/dashboard.html.twig', [
            'tricks' => $tricks,
        ]);
    }
}

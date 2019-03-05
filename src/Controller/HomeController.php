<?php

namespace App\Controller;

use App\Entity\Trick;
use App\Repository\ImageRepository;
use App\Repository\TrickRepository;
use App\Repository\VideoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(TrickRepository $repo)
    {
        $tricks = $repo->findAll();
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'tricks' => $tricks,
        ]);
    }

    /**
     * @Route("/trick/{id}", name="trick_show")
     */
    public function show(Trick $trick, ImageRepository $repo_image, VideoRepository $repo_video)
    {
        $images = $repo_image->findBy(array('trick' => $trick->getId()));
        $videos = $repo_video->findBy(array('trick' => $trick->getId()));
        return $this->render('home/show.html.twig', [
            'trick' => $trick,
            'images' => $images,
            'videos' => $videos,
        ]);
    }
}

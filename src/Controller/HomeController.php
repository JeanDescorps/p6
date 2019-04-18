<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Trick;
use App\Form\CommentType;
use App\Repository\CommentRepository;
use App\Repository\ImageRepository;
use App\Repository\TrickRepository;
use App\Repository\VideoRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * Display the home page
     *
     * @Route("/", name="home")
     *
     * @param TrickRepository $repo
     *
     * @return Response
     */

    public function index(TrickRepository $repo)
    {
        $tricks = $repo->findAll();
        return $this->render('home/index.html.twig', [
            'tricks' => $tricks,
        ]);
    }

    /**
     * Display the single trick page with trick data and the add comment form
     *
     * @Route("/trick/{id}", name="trick_show")
     *
     * @param Trick $trick
     * @param ImageRepository $repo_image
     * @param VideoRepository $repo_video
     * @param CommentRepository $repo_comment
     * @param Request $request
     * @param ObjectManager $manager
     *
     * @return Response
     */
    public function show(Trick $trick, ImageRepository $repo_image, VideoRepository $repo_video, CommentRepository $repo_comment, Request $request, ObjectManager $manager)
    {
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setCreatedAt(new \DateTime())
                ->setTrick($trick)
                ->setUser($this->getUser());
            $manager->persist($comment);
            $manager->flush();
            return $this->redirectToRoute('trick_show', ['id' => $trick->getId(), '_fragment' => $comment->getId()]);
        }
        $images = $repo_image->findBy(array('trick' => $trick->getId()));
        $videos = $repo_video->findBy(array('trick' => $trick->getId()));
        $comments = $repo_comment->findBy(array('trick' => $trick->getId()));
        return $this->render('home/show.html.twig', [
            'trick' => $trick,
            'images' => $images,
            'videos' => $videos,
            'comments' => $comments,
            'form' => $form->createView(),
        ]);
    }
}

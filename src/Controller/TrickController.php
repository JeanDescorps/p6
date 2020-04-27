<?php

namespace App\Controller;

use App\Entity\Trick;
use App\Form\TrickType;
use App\Repository\ImageRepository;
use App\Service\Paging;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TrickController extends AbstractController
{
    /**
     * Find user tricks
     *
     * @Route("/profile/trick/{page<\d+>?1}", name="trick_user")
     *
     * @param [type] $page
     * @param Paging $paging
     *
     * @return Response
     */
    public function find($page, Paging $paging): Response
    {
        $paging->setEntityClass(Trick::class)
            ->setCurrentPage($page)
            ->setLimit($this->getParameter('trick_limit'))
            ->setCriteria(['user' => $this->getUser()]);

        return $this->render('trick/user-trick.html.twig', [
            'paging' => $paging,
        ]);
    }

    /**
     * Find all tricks
     *
     * @Route("/admin/trick/{page<\d+>?1}", name="admin_trick")
     *
     * @param [type] $page
     * @param Paging $paging
     *
     * @return Response
     */
    public function findAll($page, Paging $paging): Response
    {
        $paging->setEntityClass(Trick::class)
            ->setCurrentPage($page)
            ->setLimit($this->getParameter('trick_limit'));

        return $this->render('trick/admin-trick.html.twig', [
            'paging' => $paging,
        ]);
    }

    /**
     * Add trick
     *
     * @Route("/profile/add-trick", name="trick_add")
     *
     * @param Request $request
     * @param EntityManagerInterface $manager
     *
     * @return Response
     */
    public function add(Request $request, EntityManagerInterface $manager): Response
    {
        $trick = new Trick();
        $path = $this->getParameter('images_directory');
        $form = $this->createForm(TrickType::class, $trick);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if ($form->get('file')->getData() === null) {
                $image = 'default-trick.jpg';
                $trick->setImage($image);
            }

            $trick->setPath($path);

            for ($i = 0; $i < 8; $i++) {
                foreach ($trick->getImages() as $image) {
                    $image->setPath($path);
                }
            }

            for ($i = 0; $i < 8; $i++) {
                foreach ($trick->getVideos() as $video) {
                    if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $video->getUrl(), $match)) {
                        $video_id = $match[1];
                        $video->setUrl('https://www.youtube.com/embed/' . $video_id);
                    }
                }
            }

            $createdAt = new \DateTime();

            $trick->setCreatedAt($createdAt)
                ->setUser($this->getUser());

            $manager->persist($trick);
            $manager->flush();
            $this->addFlash('success', 'Votre trick a été ajouté avec succés !');
            return $this->redirectToRoute('trick_user');
        }

        return $this->render('trick/add-trick.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Edit trick
     *
     * @Route("/trick/{id}/edit", name="trick_edit")
     * @Security(
     *      "user === trick.getUser() || is_granted('ROLE_ADMIN')",
     *      message = "Ce trick ne vous appartient pas !"
     * )
     *
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @param Trick $trick
     * @param ImageRepository $repo_image
     *
     * @return Response
     */
    public function edit(Request $request, EntityManagerInterface $manager, Trick $trick, ImageRepository $repo_image): Response
    {
        $path = $this->getParameter('images_directory');
        $images = $repo_image->findBy(array('trick' => $trick->getId()));
        $form = $this->createForm(TrickType::class, $trick);
        foreach ($trick->getImages() as $image) {
            $image->setPath($path);
        }
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $trick->setPath($path);

            for ($i = 0; $i < 8; $i++) {
                foreach ($trick->getImages() as $image) {
                    $image->setPath($path);
                }
            }

            for ($i = 0; $i < 8; $i++) {
                foreach ($trick->getVideos() as $video) {
                    if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $video->getUrl(), $match)) {
                        $video_id = $match[1];
                        $video->setUrl('https://www.youtube.com/embed/' . $video_id);
                    }
                }
            }

            $trick->setUpdatedAt(new \Datetime);
            $manager->flush();
            $this->addFlash('success', 'Votre trick a été modifié avec succés !');
            return $this->redirectToRoute('trick_show', ['id' => $trick->getId()]);
        }

        return $this->render('trick/edit-trick.html.twig', [
            'form' => $form->createView(),
            'trick' => $trick,
            'images' => $images,
        ]);

    }

    /**
     * Delete trick for user
     *
     * @Route("/profile/trick/delete/{id}", name="trick_delete")
     * @Security(
     *      "user === trick.getUser()",
     *      message = "Ce trick ne vous appartient pas !"
     * )
     *
     * @param EntityManagerInterface $manager
     * @param Trick $trick
     *
     * @return Response
     */
    public function delete(EntityManagerInterface $manager, Trick $trick): Response
    {
        $manager->remove($trick);
        $manager->flush();
        $this->addFlash('success', 'Le trick a été supprimé.');
        return $this->redirectToRoute('trick_user');
    }

    /**
     * Delete trick for admin
     *
     * @Route("/admin/trick/delete/{id}", name="trick_admin_delete")
     *
     * @param EntityManagerInterface $manager
     * @param Trick $trick
     *
     * @return Response
     */
    public function deleteAll(EntityManagerInterface $manager, Trick $trick): Response
    {
        $manager->remove($trick);
        $manager->flush();
        $this->addFlash('success', 'Le trick a été supprimé.');
        return $this->redirectToRoute('admin_trick');
    }
}

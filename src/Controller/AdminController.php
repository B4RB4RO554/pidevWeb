<?php

namespace App\Controller;

use App\Repository\EventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Post;
use App\Entity\Commentaire;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;


class AdminController extends AbstractController
{
        #[Route('/admin', name: 'app_admin')]
    public function index(EventRepository $eventRepository): Response
    {
        return $this->render('admin/index.html.twig', [
            'events' => $eventRepository->findBy([], ['datedebut' => 'DESC']),

        ]);
    }
    #[Route('/admin1', name: 'app_admin1')]
    public function index1(EntityManagerInterface $entityManager): Response
    {
        $posts = $entityManager->getRepository(Post::class)->findAll();
        return $this->render('admin1/index.html.twig', [
            "posts" => $posts,
        ]);
    }

    #[Route('/{id}/delete', name: 'app_admin_delete', methods: ['GET'])]
    public function delete(Request $request, Post $post, EntityManagerInterface $entityManager): Response
    {
        //delete all comments
        $comments = $entityManager->getRepository(Commentaire::class)->findBy(['post' => $post]);
        foreach ($comments as $comment) {
            $entityManager->remove($comment);
        }
        $entityManager->remove($post);
        $entityManager->flush();
        return $this->redirectToRoute('app_admin', [], Response::HTTP_SEE_OTHER);
    }
}

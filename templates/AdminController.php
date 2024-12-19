<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\Commentaire;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'app_admin')]
    public function index(EntityManagerInterface $entityManager): Response
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

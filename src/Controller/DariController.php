<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\Comment;
use App\Form\CommentType;
use App\Repository\EventRepository;
use App\Repository\CommentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DariController extends AbstractController
{
    #[Route('/dari', name: 'app_dari')]
    public function index2(EventRepository $eventRepository): Response
    {

        return $this->render('dari/index.html.twig', [
            'events' => $eventRepository->findAll(),
        ]);



    }
    #[Route('/{id}', name: 'app_event_show', methods: ['GET'])]
    public function show(Event $event): Response
    {
        return $this->render('dari/index2.html.twig', [
            'event' => $event,
        ]);
    }
    /*#[Route('/{id}', name: 'app_event_show', methods: ['GET'])]
    public function show(Event $event): Response
    {
        return $this->render('event/show.html.twig', [
            'event' => $event,
        ]);
    }*/
    /*#[Route('/new', name: 'ajout_comment_new', methods: ['GET', 'POST'])]
    public function ajout(Request $request, CommentRepository $commentRepository): Response
    {
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $commentRepository->save($comment, true);

            return $this->redirectToRoute('app_comment_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('dari/index2.html.twig', [
            'comment' => $comment,
            'form' => $form,
        ]);
    }*/
}

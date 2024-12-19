<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\User;
use App\Form\EventType;
use App\Repository\EventRepository;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Bridge\Google\Transport\GmailSmtpTransport;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Dompdf\Dompdf;
use Dompdf\Options;


#[Route('/event')]
class EventController extends AbstractController
{
    #[Route('/pdfevent', name: 'app_eventPDF', methods: ['GET'])]
    public function pdf(EventRepository $eventRepository,ManagerRegistry $managerRegistry) : Response
    {

        $html = $this->renderView('event/eventPDF.html.twig', [
            'events' =>$managerRegistry->getManager()->getRepository(Event::class)->findAll(),
        ]);

        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $response = new Response($dompdf->output());
        $response->headers->set('Content-Type', 'application/pdf');
        $response->headers->set('Content-Disposition', 'attachment;filename=listEvent.pdf ');

        return $response;

    }
    #[Route('/', name: 'app_event_index', methods: ['GET'])]
    public function index(EventRepository $eventRepository): Response
    {
        return $this->render('event/index.html.twig', [
            'events' => $eventRepository->findBy([], ['datedebut' => 'DESC']),
        ]);
    }

    #[Route('/new', name: 'app_event_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EventRepository $eventRepository): Response
    {
        $event = new Event();
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $photoFile = $form->get('photo')->getData();
            if ($photoFile) {
                $newFilename = uniqid() . '.' . $photoFile->guessExtension();
    
                // Move the file to the directory where your application can access it
                $photoFile->move(
                    $this->getParameter('photo_directory'),
                    $newFilename
                );
    
                // Update the entity with the photo filename
                $event->setPhoto($newFilename);
            }
    
            $eventRepository->save($event, true);

            //$this->sendEmail() ;
    
            return $this->redirectToRoute('app_event_index', [], Response::HTTP_SEE_OTHER);
        }
    
        return $this->renderForm('event/new.html.twig', [
            'event' => $event,
            'form' => $form,
        ]);
    }
    

    #[Route('/{id}', name: 'app_event_show', methods: ['GET'])]
    public function show(Event $event): Response
    {
        return $this->render('event/show.html.twig', [
            'event' => $event,
        ]);
    }

    #[Route('/{id}/edit1', name: 'app_event_edit1', methods: ['GET', 'POST'])]
    public function edit1(Request $request, Event $event, EventRepository $eventRepository): Response
    {
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $photoFile = $form->get('photo')->getData();
            if ($photoFile) {
                $newFilename = uniqid() . '.' . $photoFile->guessExtension();
    
                // Move the file to the directory where your application can access it
                $photoFile->move(
                    $this->getParameter('photo_directory'),
                    $newFilename
                );
    
                // Update the entity with the photo filename
                $event->setPhoto($newFilename);
            }
    
            $eventRepository->save($event, true);
            
            return $this->redirectToRoute('app_admin', [], Response::HTTP_SEE_OTHER);
        }
    
        return $this->renderForm('event/edit2.html.twig', [
            'event' => $event,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_event_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Event $event, EventRepository $eventRepository): Response
    {
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $photoFile = $form->get('photo')->getData();
            if ($photoFile) {
                $newFilename = uniqid() . '.' . $photoFile->guessExtension();
    
                // Move the file to the directory where your application can access it
                $photoFile->move(
                    $this->getParameter('photo_directory'),
                    $newFilename
                );
    
                // Update the entity with the photo filename
                $event->setPhoto($newFilename);
            }
    
            $eventRepository->save($event, true);
            
            return $this->redirectToRoute('app_event_index', [], Response::HTTP_SEE_OTHER);
        }
    
        return $this->renderForm('event/edit.html.twig', [
            'event' => $event,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_event_delete', methods: ['POST'])]
    public function delete(Request $request, Event $event, EventRepository $eventRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$event->getId(), $request->request->get('_token'))) {
            $eventRepository->remove($event, true);
        }

        return $this->redirectToRoute('app_event_index', [], Response::HTTP_SEE_OTHER);
    }


public function sendEmail(){
        $email=(new Email())
            ->from('dellelbrens@gmail.com')
            ->to('jackgandalf24@gmail.com')
            ->subject('Event')
            ->text('A new event has been created, you should check it');
            $transport= new GmailSmtpTransport('dellelbrens@gmail.com','ppauinhndhweoguo');
            $mailer=new Mailer($transport);
            $mailer->send($email);
}
}

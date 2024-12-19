<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class Home2Controller extends AbstractController
{
    
    #[Route('/faq', name: 'faq_')]
    public function faq(): Response
    {
        return $this->render('home/faq.html.twig', [
            // 'controller_name' => 'HomeController',
        ]);
    }
}

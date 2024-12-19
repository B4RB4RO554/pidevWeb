<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;


// /**
// * @IsGranted("IS_AUTHENTICATED_FULLY") 
// */
class AnnoncesController extends AbstractController
{
    #[Route('/annonces', name: 'app_annonces')]
    public function index(): Response
    {
        if (!$this->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute('app_home');
        }
        return $this->render('annonces/annonces.html.twig', [
            'controller_name' => 'AnnoncesController',
        ]);
    }
}

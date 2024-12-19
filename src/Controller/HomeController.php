<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        $user = $this->getUser();
        if ($user)
        {
            if (in_array('ROLE_ADMIN', $user->getRoles())) {
                return $this->redirectToRoute('app_home_back');
            }else {
                return $this->redirectToRoute('app_home_front');
            }
        }
        else {
            return $this->redirectToRoute('app_login');
        }

//        return $this->render('home/index.html.twig', [
//            'controller_name' => 'HomeController',
//        ]);
    }
}

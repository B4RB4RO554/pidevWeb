<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Form\EditUserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
#[Route('/user')]
class UserController extends AbstractController
{
    #[Route('/', name: 'app_user_index', methods: ['GET'])]
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_user_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }
    #[Route('/{id}/edit', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }
    #[Route('/Profile', name: 'app_Profile')]
    public function Profile(Request $request,EntityManagerInterface $entityManager): Response
    {
        $email =$request->getSession()->get(Security::LAST_USERNAME);
        $user=$entityManager->getRepository(User::class)->findOneBy(['email' => $email]);
        return $this->render('user/Profile.html.twig', [
            'user' => $user,
        ]);
    }
    #[Route('/Editt/{id}', name: 'app_edit')]
    public function EditUser(Request $request,EntityManagerInterface $entityManager,$id): Response
    {
        $user=$entityManager->getRepository(User::class)->find($id);
        $form = $this->createForm(EditUserType::class);
        $form->get('email')->setData($user->getEmail());
        $form->get('nom')->setData($user->getNom());
        $form->get('prenom')->setData($user->getPrenom());
        $form->get('numTel')->setData($user->getNumTel());
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
           
            $user->setEmail($form->get('email')->getData());
            $user->setNom($form->get('nom')->getData());
            $user->setPrenom($form->get('prenom')->getData());
            $user->setNumTel($form->get('numTel')->getData());
            $entityManager->flush();
            $request->getSession()->set(Security::LAST_USERNAME, $user->getEmail());
            return $this->redirectToRoute('app_Profile');
        }
        return $this->renderForm('user/EditUser.html.twig', [
            'form' => $form
        ]);
    }
    #[Route('/{id}', name: 'app_user_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }



    #[Route('/{id}', name: 'app_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    }
}

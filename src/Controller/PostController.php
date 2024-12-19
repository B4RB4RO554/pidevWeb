<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use App\Entity\Commentaire;
use App\Entity\Utilisateur;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/post')]
class PostController extends AbstractController
{
    #[Route('/list/{page}', name: 'app_post_list')]
    public function list(EntityManagerInterface $entityManager, int $page = 1): Response
    {
        $limit = 5; // Number of posts per page
        $offset = ($page - 1) * $limit;
    
        // Get total count of posts
        $totalCount = $entityManager->getRepository(Post::class)
            ->createQueryBuilder('p')
            ->select('COUNT(p.id)')
            ->getQuery()
            ->getSingleScalarResult();
    
        // Calculate total pages
        $totalPages = ceil($totalCount / $limit);
    
        // Fetch posts for the current page
        $posts = $entityManager->getRepository(Post::class)
            ->createQueryBuilder('p')
            ->orderBy('p.timestamp', 'DESC')
            ->setMaxResults($limit)
            ->setFirstResult($offset)
            ->getQuery()
            ->getResult();
    
        return $this->renderForm('post/posts.html.twig', [
            'posts' => $posts,
            'current_user' => 1,
            'page' => $page,
            'totalPages' => $totalPages,
        ]);
    }

    #[Route('/new', name: 'app_post_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $post = new Post();
        $post->setContenu('');
        //get static user with id 1
        $post->setAuthor($entityManager->getRepository(Utilisateur::class)->find(1));
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $post->setNbLike(0);
            $file = $form['image']->getData();
            $destination = $this->getParameter('kernel.project_dir') . '/public/uploads';
            $fileName = md5(uniqid()) . '.' . $file->guessExtension();
            $file->move($destination, $fileName);
            $post->setImage('uploads/' . $fileName);
            $post->setTimestamp(new \DateTime());
            $entityManager->persist($post);
            $entityManager->flush();

            return $this->redirectToRoute('app_post_list', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('post/new.html.twig', [
            'post' => $post,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_post_show', methods: ['GET'])]
    public function show(int $id, EntityManagerInterface $entityManager): Response
    {
        $post = $entityManager->getRepository(Post::class)->find($id);
        if (!$post) {
            throw $this->createNotFoundException('The post does not exist');
        }
    
        $comments = $entityManager->getRepository(Commentaire::class)->findBy(['post' => $post]);
        $badwords = file_get_contents($this->getParameter('kernel.project_dir') . '/config/badwords.txt');
        $badwords = explode("\n", $badwords);
        
        foreach ($comments as $comment) {
            $commentText = strtoupper($comment->getValeur());
            $badwordsUpper = array_map('strtoupper', $badwords);
            $censoredComment = str_ireplace($badwordsUpper, '****', $commentText);
            $comment->setValeur($censoredComment);
        }
    
        return $this->render('post/show.html.twig', [
            'post' => $post,
            'current_user' => 1,
            'comments' => $comments,
        ]);
    }
    
        #[Route('/{id}/edit', name: 'app_post_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Post $post, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form['image']->getData();
            $destination = $this->getParameter('kernel.project_dir') . '/public/uploads';
            $fileName = md5(uniqid()) . '.' . $file->guessExtension();
            $file->move($destination, $fileName);
            $post->setImage('uploads/' . $fileName);
            $entityManager->flush();
            return $this->redirectToRoute('app_post_list', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('post/edit.html.twig', [
            'post' => $post,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/delete', name: 'app_post_delete', methods: ['GET'])]
    public function delete(Request $request, Post $post, EntityManagerInterface $entityManager): Response
    {
        //delete all comments
        $comments = $entityManager->getRepository(Commentaire::class)->findBy(['post' => $post]);
        foreach ($comments as $comment) {
            $entityManager->remove($comment);
        }
        $entityManager->remove($post);
        $entityManager->flush();
        return $this->redirectToRoute('app_post_list', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/like', name: 'app_post_like')]
    public function like(Request $request, Post $post, EntityManagerInterface $entityManager): Response
    {
        $post->setNbLike($post->getNbLike() + 1);
        $entityManager->flush();
        return $this->redirectToRoute('app_post_list', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/comment/add', name: 'app_post_comment_add')]
    public function addComment(Request $request, Post $post, EntityManagerInterface $entityManager): Response
    {
        $comment = new Commentaire();
        if($request->request->get('comment') == ""){
            return $this->redirectToRoute('app_post_list', ['id' => $post->getId()], Response::HTTP_SEE_OTHER);
        }
        $comment->setValeur($request->request->get('comment'));
        $comment->setUtilisateur($entityManager->getRepository(Utilisateur::class)->find(1));
        $comment->setPost($post);
        $comment->setTimestamp(new \DateTime());
        $entityManager->persist($comment);
        $entityManager->flush();
        
        return $this->redirectToRoute('app_post_show', ['id' => $post->getId()], Response::HTTP_SEE_OTHER);
    }


    //translate function that will take text in the bod post and return the translated text
    #[Route('/translate', name: 'app_post_translate')]
    public function translate(Request $request): JsonResponse
    {
        // Get the text to be translated from the request
        $text = $request->request->get('text');

        // Set up the Symfony HttpClient
        $client = HttpClient::create();

        // Make the request to RapidAPI Deep Translate
        $response = $client->request('POST', 'https://deep-translate1.p.rapidapi.com/language/translate/v2', [
            'headers' => [
                'X-RapidAPI-Host' => 'deep-translate1.p.rapidapi.com',
                'X-RapidAPI-Key' => '8243c79598msh91abfd047d54e59p15bcc0jsn0bfdc937338a',
                'Content-Type' => 'application/json'
            ],
            'json' => [
                'q' => $text,
                'source' => 'fr',
                'target' => 'en'
            ],
        ]);

        // Decode the JSON response
        $translatedText = $response->toArray();

        // Return translated text as JSON response
        return $this->json(['translatedText' => $translatedText['data']['translations']['translatedText']]);
    }

}
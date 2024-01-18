<?php

namespace App\Controller;

use App\Form\PostType;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostController extends AbstractController
{
    

    // Route 
    #[Route('/post/{slug}/edit', name: 'post_edit')]
    public function edit(
        $slug,
        Request $request,
        PostRepository $postRepository,
        EntityManagerInterface $em
    ): Response
    {
        $post = $postRepository->findAll([
            'name' => $request->get('post')
        ]);


        //formulaire pour éditer un post
        $form = $this->createForm(PostType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $em->$this->getDoctrine()->getManager();
            $em->persist($slug);
            $em->flush();
        };
        

        return $this->render('post/edit.html.twig', [
            'slug' => $slug,
        ]);
    }
}

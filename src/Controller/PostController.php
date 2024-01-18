<?php

namespace App\Controller;

use App\Form\PostType;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostController extends AbstractController
{
    

    // Route 
    #[Route('/post/{slug}/edit', name: 'post_edit')]
    public function edit(
        Request $request,
        PostRepository $postRepository,
        EntityManagerInterface $em
    ): Response
    {
        $post = $postRepository->findOneBy([
            'slug' => $request->get('slug')
        ]);


        //formulaire pour éditer un post
        $form = $this->createForm(PostType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            if ($form->get('image')->getData()) {
                $imageFile = $form->get('image')->getData();
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = $originalFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();
                try {
                    $imageFile->move(
                        $this->getParameter('uploads_directory'),
                        $newFilename
                    );
                    $post->setImage($newFilename);
                } catch (FileException $e) {
                    $this->addFlash('danger', 'Une erreur est survenue lors de l\'upload de votre image');
                }
            }



            $em->$this->get()->getData();
            $em->persist($post);
            $em->flush();
        };
        

        return $this->render('post/edit.html.twig', [
            'post' => $post,
            'editedForm' => $form,
        ]);
    }
}

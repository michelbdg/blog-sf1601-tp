<?php

namespace App\Controller;

use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CategoryController extends AbstractController
{
    #[Route('/{category}/edit', name: 'category_edit')]
    public function edit(
        Request $request,
        CategoryRepository $categoryRepository,
        EntityManagerInterface $em // To save the category edited
    ): Response
    {
        $category = $categoryRepository->findOneBy([
            'name' => $request->get('category')
        ]);

        // Edit form + proccess
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
           $category->setName($form->get('name')->getData());
           $em->persist($category);
           $em->flush(); 
            
           // Redirect to the category page
            return $this->redirectToRoute('category', [
                'category' => $category->getName()
            ]);
        }


        // Return the view
        return $this->render('category/edit.html.twig', [
            // Pass the category object to the view
            'category' => $category,
            'editForm' => $form
        ]);
    }

    // Route to add a new category
    #[Route('/new-category', name: 'category_new')]
    public function new(
        // Inject the request object to get the data from the form
        Request $request,
        // Add the EntityManagerInterface to save the category
        EntityManagerInterface $em,
    ): Response
    {
        // TODO Add the form here

        // TODO Add the form proccess here

        // Return the view
        return $this->render('category/new.html.twig', [
            // Pass the form to the view
        ]);
    }

    // Route to delete a category
}

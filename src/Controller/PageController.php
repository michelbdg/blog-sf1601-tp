<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\PostRepository;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PageController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(
        PostRepository $postRepository,
        CategoryRepository $categoryRepository
    ): Response
    {
        return $this->render('page/home.html.twig', [
            'posts' => $postRepository->findAll(),
            'categories' => $categoryRepository->findAll()
        ]);
    }

    // Route for displaying a single category wi
    #[Route('/{category}', name: 'category')]
    public function category(
        Request $request,
        CategoryRepository $categoryRepository,
        PostRepository $postRepository,
    ): Response
    {
        // Find the category by its name
        $category = $categoryRepository->findOneBy([
            'name' => $request->get('category')
        ]);
        return $this->render('page/category.html.twig', [
            // Pass the category name to the view
            'title' => $category->getName(),
            // Pass all posts in the category to the view
            'posts' => $postRepository->findBy([
                'category' => $category
            ]
            )
        ]);
    }
}
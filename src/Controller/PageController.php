<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\PostRepository;
use App\Repository\CategoryRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PageController extends AbstractController
{
    #[Route('/', name: 'home', methods: ['GET'])]
    public function index(
        Request $request,
        PostRepository $postRepository,
        CategoryRepository $categoryRepository,
        PaginatorInterface $paginator
    ): Response {
        $posts = $paginator->paginate(
            $postRepository->findAll(), // Request
            $request->query->getInt('page', 1), // Page number
            9 // Limit per page
        );
        return $this->render('page/home.html.twig', [
            'posts' => $posts,
            'categories' => $categoryRepository->findAll()
        ]);
    }

    // Route for displaying a single category (NE PAS REECRIRE TOUS LES COMMENTAIRES)
    #[Route('/{category}', name: 'category', methods: ['GET'])]
    public function category(
        Request $request,
        CategoryRepository $categoryRepository,
        PostRepository $postRepository,
    ): Response {
        $category = $categoryRepository->findOneBy([
            'name' => $request->get('category')
        ]);
        return $this->render('page/category.html.twig', [
            'category' => $category,
            'posts' => $postRepository->findBy(['category' => $category]
            )
        ]);
    }

    #[Route('/post/{slug}', name: 'post_show')]
    public function show(
        $slug,
        Request $request,
        PostRepository $postRepository,
        ): Response
    {
        $post = $postRepository->findOneBy([
            'slug' =>$request->get('slug')
        ]);

        return $this->render('post/post.html.twig', [
            'post' => $post,
        ]);
    }

}

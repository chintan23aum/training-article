<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Category;
use App\Repository\ArticleRepository;
use App\Service\ArticleService;
use App\Service\CategoryService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class HomeController extends AbstractController
{
    private $entityManager;
    private $categoryService;
    private $articleService;

    public function __construct(EntityManagerInterface $entityManager,CategoryService $categoryService, ArticleService $articleService)
    {
        $this->entityManager = $entityManager;
        $this->categoryService = $categoryService;
        $this->articleService = $articleService;
    }

    /**
     * Home page 
     */
    public function index(): Response
    {
        return $this->render('article/index.html.twig', [
            'categories' => $this->categoryService->getAllCategory(),
            'articles' => $this->articleService->getAllArticle(),
            'category_id' =>0,
        ]);
    }

    public function showarticlelist(Category $category_id = null): Response
    {
        $articles = $this->articleService->getArticleByCategory($category_id->getId());

        return $this->render('article/index.html.twig', [
            'categories' => $this->categoryService->getAllCategory(),
            'articles' => $articles,
            'category_id' => $category_id->getId(),
        ]);
    }

    public function topsearch(Request $request, ArticleRepository $articleRepository): Response
    {
        $articles = $articleRepository->findBySearchTerm($request->request->get('searchVal'));

        $data = ['message' => 'AJAX request received!',
            'data' => $request->request->get('searchVal'),
            'html'=> $this->renderView('article/_serach_res.html.twig', [
                'articles'=>$articles
            ])
        ];
        return $this->json($data);
    }


}

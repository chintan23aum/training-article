<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Category;
use App\Service\ArticleService;
use App\Service\CategoryService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
        return $this->render('home/index.html.twig', [
            'categories' => $this->categoryService->getAllCategory(),
            'articles' => $this->articleService->getAllArticle(),
            'category_id' =>0,
        ]);
    }

    public function showarticlelist(?Category $category_id = null): Response
    {
        $articles = $this->articleService->getArticleByCategory($category_id->getId());

        return $this->render('home/index.html.twig', [
            'categories' => $this->categoryService->getAllCategory(),
            'articles' => $articles,
            'category_id' => $category_id->getId(),
        ]);
    }

}

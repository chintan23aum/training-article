<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\ArticleRepository;
use App\Service\ArticleService;
use App\Service\CategoryService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DisplayController extends AbstractController
{
    private $articleRepository;
    private $categoryService;
    private $articleService;

    public function __construct(CategoryService $categoryService, ArticleRepository $articleRepository, ArticleService $articleService)
    {
        $this->articleRepository = $articleRepository;
        $this->categoryService = $categoryService;
        $this->articleService = $articleService;
    }

    public function index(CategoryService $categoryService): Response
    {

        return $this->render('display/index.html.twig', [
            'controller_name' => 'DisplayController',
        ]);
    }

    public function ajaxlist(Category $category_id = null,Request $request): Response
    {
        $categoryId= 0;
        if($category_id==null){
            $articles = $this->articleService->getAllArticle();
        } else {
            $articles = $this->articleRepository->findByCategory($category_id->getId(), json_decode($request->get('parentIds'), true));
            $categoryId =$category_id->getId();
        }

        $data = ['message' => 'AJAX request received!',
            'html'=> $this->renderView('display/ajax_list.html.twig',[
                'categories' => $this->categoryService->getAllCategory(),
                'articles' => $articles,
                'category_id' => $categoryId,
                'totalCount' => count($articles),
            ])
        ];
        return $this->json($data);
    }

    public function advanceSearch(Request $request): Response
    {
        $categoryId= 0;
        $articles = $this->articleRepository->findByAdvanceSearch($request->get('search'));

        $data = ['message' => 'AJAX request received!',
            'html'=> $this->renderView('display/ajax_list.html.twig',[
                'categories' => $this->categoryService->getAllCategory(),
                'articles' => $articles,
                'category_id' => $categoryId,
                'totalCount' => count($articles),
            ])
        ];
        return $this->json($data);
    }
}

<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ArticleRepository;

class ArticleController extends AbstractController
{
 
    public function index(): Response
    {
        return $this->render('article/index.html.twig', [
            'controller_name' => 'ArticleController',
        ]);
    }

    public function list(ArticleRepository $articleRepository): Response
    { 
        return $this->render('article/list.html.twig',[
            'articles' => $articleRepository->findAll()
        ]);
    }
}

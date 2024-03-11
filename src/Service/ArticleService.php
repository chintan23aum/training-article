<?php

namespace App\Service;

use App\Entity\Article;
use App\Entity\ArticleLog;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class ArticleService
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getAllArticle()
    {
        return $this->entityManager->getRepository(Article::class)->findAll();
    }

    public function getArticleByCategory(int $category_id)
    {
        return $this->entityManager->getRepository(Article::class)->findBy(['category' => $category_id]);
    }

    public function getArticleDetail(int $id)
    {
        return $this->entityManager->getRepository(Article::class)->findBy(['id' => $id]);
    }

    public function getArticleLikes(int $id, int $user)
    {
        $totalrow= $this->entityManager->getRepository(ArticleLog::class)->findBy(['article' => $id, 'user'=>$user]);
        return count($totalrow);
    }


}
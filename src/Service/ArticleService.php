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

    public function getArticleLikes(int $id)
    {
        $totalrow= $this->entityManager->getRepository(ArticleLog::class)->findBy(['article' => $id]);
        return count($totalrow);
    }


}
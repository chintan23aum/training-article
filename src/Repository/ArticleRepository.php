<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Article>
 *
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
    }

    /*
     * Advance Search
     * */
    public function findByAdvanceSearch($searchTerms): array
    {

        $queryBuilder =  $this->createQueryBuilder('a');

        if($searchTerms['all']!=""){
            $queryBuilder->andWhere('a.title LIKE :val')
                ->orWhere('a.tags LIKE :val')
                ->setParameter('val', '%' . $searchTerms['all'] . '%');
        }

        if($searchTerms['category']!=""){
            $queryBuilder->andWhere('a.category = :category')
            ->setParameter('category', $searchTerms['category']);
        }

        if($searchTerms['title']!=""){
            $queryBuilder->andWhere('a.title LIKE :title')
            ->setParameter('title', '%' . $searchTerms['title'] . '%');
        }
        if($searchTerms['tags']!=""){
            $queryBuilder->andWhere('a.tags LIKE :tags')
                ->setParameter('tags', '%' . $searchTerms['tags'] . '%');
        }
        $articles = $queryBuilder-> orderBy('a.id', 'ASC')
            ->getQuery()
            ->getResult();

            return $articles;
    }

    public function findByCategory($categoryId, $parentIds): array
    {

        $result = $this->createQueryBuilder('a')
            ->andWhere('a.category = :category_id')
            ->orWhere('a.sub_categories LIKE :val')
            ->setParameter('category_id', $categoryId)
            ->setParameter('val', '%"' . $categoryId . '"%')
            ->orderBy('a.id', 'ASC')
            ->getQuery()
            ->getResult();

        $parentIds[]= $categoryId;
        $matchingEntities = array_filter($result, function ($article) use ($parentIds) {
            if($article->getSubCategories()==null){
                return $article;
            }

            $allids = $article->getSubCategories();
            $allids[] = $article->getCategory()->getId();

            foreach($parentIds as $id){
                if(!in_array($id, $allids)){
                    return false;
                }
            }
            return $article;

        });
        return $matchingEntities;

    }

}

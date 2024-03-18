<?php

namespace App\Repository;

use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;

/**
 * @extends ServiceEntityRepository<Category>
 *
 * @method Category|null find($id, $lockMode = null, $lockVersion = null)
 * @method Category|null findOneBy(array $criteria, array $orderBy = null)
 * @method Category[]    findAll()
 * @method Category[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }

    public function findByCateogryIds($ids): array
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.id IN (:ids)')
            ->setParameter('ids', $ids)
            ->getQuery()
            ->getResult();
    }

    public function findByParentCategoryId(int $categoryId): array
    {
        // Fetch all entities
        $entities = $this->findAll();

        // Filter entities based on the category ID
        $matchingEntities = array_filter($entities, function ($entity) use ($categoryId) {
            // Check if parentCategory is null
            if ($entity->getParentCategory() === null) {
                return false;
            }
            // Check if the category ID is in the parentCategory array
            return in_array($categoryId, $entity->getParentCategory(), true);
        });

        return $matchingEntities;
    }

    public function getMainCategory(): array
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.parent_category IS NULL')
            ->getQuery()
            ->getResult();
    }



//    /**
//     * @return Category[] Returns an array of Category objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Category
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}

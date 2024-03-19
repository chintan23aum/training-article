<?php

namespace App\Service;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;

class CategoryService
{
    private $entityManager;
    private $categoryRepository;

    public function __construct(EntityManagerInterface $entityManager, CategoryRepository $categoryRepository)
    {
        $this->entityManager = $entityManager;
        $this->categoryRepository = $categoryRepository;
    }

    public function getAllCategory()
    {
        return $this->entityManager->getRepository(Category::class)->findAll();
    }

    private function getNestedCategory(Category $categoryId=null,$parentIds =[]): array
    {
        $result = [];
        $parentIds[]= $categoryId->getId();
        $children = $this->categoryRepository->findByParentCategoryId($categoryId->getId());
        foreach ($children as $child) {
            $childData = [
                'id' => $child->getId(),
                'name' => $child->getName(),
                'parent_ids'=>$parentIds
                // Add other properties as needed
            ];
            $nestedChildren = $this->getNestedCategory($child,$parentIds);
            if (!empty($nestedChildren)) {
                $childData['child'] = $nestedChildren;
            }
            $result[] = $childData;
        }
        return $result;
    }

    public function getAllCategoryArr(): array
    {
        $menu = [];
        $resArr= $this->categoryRepository->getMainCategory();
        if($resArr){
            foreach($resArr as $row) {
                $tmp=['main'=>$row];
                $tmp['child']=$this->getNestedCategory($row);
                $menu[]=$tmp;
            }
        }
        return $menu;
    }

}
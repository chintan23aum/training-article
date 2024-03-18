<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

 
class CategoryController extends AbstractController
{
    private $categoryRepository;
    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }
 
    public function index(): Response
    {
        return $this->render('category/index.html.twig', [
            'categories' => $this->categoryRepository->findAll(),
        ]);
    }

    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category,[
            'categories' => $this->categoryRepository->findAll(),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $selectedCategories = $form->get('parent_category')->getData();
            $selectedCategoryIds = [];
            foreach ($selectedCategories as $selectedCategory) {
                $selectedCategoryIds[] = $selectedCategory->getId();
            }
            $category->setParentCategory($selectedCategoryIds);

            $entityManager->persist($category);
            $entityManager->flush();

            return $this->redirectToRoute('app_category_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('category/new.html.twig', [
            'category' => $category,
            'form' => $form,
        ]);
    }

    public function show(Category $category, CategoryRepository $categoryRepository): Response
    {
        $parentCategoryIds = $category->getParentCategory();
        $parentCategories = $categoryRepository->findByCateogryIds($parentCategoryIds);

        return $this->render('category/show.html.twig', [
            'category' => $category,
            'parentCategory' => $parentCategories,
        ]);
    }
   
    public function edit(Request $request, Category $category, EntityManagerInterface $entityManager): Response
    {
        $selectedCategoryIds = $category->getParentCategory();
        $form = $this->createForm(CategoryType::class, $category,[
            'categories' => $this->categoryRepository->findAll(),
            'parentCategoryData' => $selectedCategoryIds,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $selectedCategories = $form->get('parent_category')->getData();
            $selectedCategoryIds = [];
            foreach ($selectedCategories as $selectedCategory) {
                $selectedCategoryIds[] = $selectedCategory->getId();
            }
            $category->setParentCategory($selectedCategoryIds);

            $entityManager->flush();

            return $this->redirectToRoute('app_category_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('category/edit.html.twig', [
            'category' => $category,
            'form' => $form,
        ]);
    }
 
    public function delete(Request $request, Category $category, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$category->getId(), $request->request->get('_token'))) {
            $entityManager->remove($category);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_category_index', [], Response::HTTP_SEE_OTHER);
    }

    public function getCategories(int $id,CategoryRepository $categoryRepository): JsonResponse
    {
        $categories = $categoryRepository->findByParentCategoryId($id);

        // Serialize categories to JSON format
        $data = [];
        foreach ($categories as $category) {
            $data[] = [
                'id' => $category->getId(),
                'name' => $category->getName(),
            ];
        }
        return new JsonResponse($data);
    }


}

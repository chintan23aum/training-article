<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\ArticleLog;
use App\Form\ArticleType;
use App\Repository\ArticleLogRepository;
use App\Repository\CategoryRepository;
use App\Service\ArticleService;
use App\Service\CategoryService;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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

    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();
            //$subcategories = $request->request->get('subcategory');
            $parameters = $request->request->all();

            $article->setUser($this->getUser());
            $article->setSubCategories($parameters['subcategory']);
            $article->setCategory($formData->getCategory());
            $article->setTitle($formData->getTitle());
            $article->setDescription($formData->getDescription());
            $article->setTags($formData->getTags());

            $createdAt = new DateTimeImmutable();
            $article->setCreatedAt($createdAt);
            $article->setUpdatedAt($createdAt);

            $entityManager->persist($article);
            $entityManager->flush();

            return $this->redirectToRoute('app_article_list');
        }

        return $this->renderForm('article/new.html.twig', [
            'article' => $article,
            'form' => $form,
            'subCategory' => [],
            'category' => null
        ]);
    }

    public function edit(Request $request, Article $article, EntityManagerInterface $entityManager, CategoryRepository $categoryRepository): Response
    {
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        $subCategory = $article->getSubCategories($article);

      //  $subCategories = $categoryRepository->findByCateogryIds($subCategory);


        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();

            $parameters = $request->request->all();

            $article->setUser($this->getUser());
            $article->setSubCategories($parameters['subcategory']);
            $article->setCategory($formData->getCategory());
            $article->setTitle($formData->getTitle());
            $article->setDescription($formData->getDescription());
            $article->setTags($formData->getTags());

            $createdAt = new DateTimeImmutable();
            $article->setUpdatedAt($createdAt);
            $entityManager->flush();

            return $this->redirectToRoute('app_article_list');
        }

        return $this->renderForm('article/edit.html.twig', [
            'category' => $article,
            'form' => $form,
            'subCategory' => $subCategory
        ]);
    }

    public function delete(Request $request, Article $article, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$article->getId(), $request->request->get('_token'))) {
            $entityManager->remove($article);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_article_list');
    }

    public function display(Article $article,CategoryService $categoryService, ArticleService $articleService)
    {
        $likecount = $articleService->getArticleLikes($article->getId());

        $user = $this->getUser();

        return match ($user->isVerified()) {
            false => $this->render("article/verify_email.html.twig", [
                'categories' => $categoryService->getAllCategory(),
                'category_id' => $article->getCategory()->getId(),
                'article' => $article,
                'likecount' => $likecount,
            ]),
            true => $this->render('article/display.html.twig', [
                'categories' => $categoryService->getAllCategory(),
                'category_id' => $article->getCategory()->getId(),
                'article' => $article,
                'likecount' => $likecount,
            ]),
        };

    }

    public function likearticle(Article $article,ArticleService $articleService, ArticleLogRepository $articleLogRepository,EntityManagerInterface $entityManager)
    {

        $totalLike = $articleLogRepository->findOneByUser($article,$this->getUser());

        if($totalLike[1] <= 0){
            //insert new record

            $articlelog = new ArticleLog();
            $articlelog->setUser($this->getUser());
            $articlelog->setArticle($article);
            $createdAt = new DateTimeImmutable();
            $articlelog->setCreatedAt($createdAt);

            $entityManager->persist($articlelog);

            // Flush changes to the database
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_article_display',['id'=>$article->getId()]);
    }


}

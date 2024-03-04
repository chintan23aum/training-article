<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{ 
    /**
     * Home page 
     */
    public function index(): Response
    { 
        $this->denyAccessUnlessGranted("IS_AUTHENTICATED_FULLY");
        return $this->render('admin/index.html.twig');
    }
}

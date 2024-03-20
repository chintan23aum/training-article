<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class HomeController extends AbstractController
{

    /**
     * Home page 
     */
    public function index(): Response
    {
        return $this->render('display/index.html.twig', [
            'controller_name' => 'DisplayController',
        ]);
    }

}

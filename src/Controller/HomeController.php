<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends Controller
{
    /**
     * @Route("/", name="root")
     */
    public function root()
    {
        //redirection vers la route home en cas de redirection vers '/'
        return $this->redirectToRoute('home');
    }
    
    
    /**
     * @Route("/home", name="home")
     * @Route("/home/{page}", name="home_paginated")
     */
    public function index(\App\Repository\ProductRepository $productRepo, $page=1)
    {
        //Model query to get product + fanta pagination
        $products = $productRepo->findPaginated($page);
        //Send to a template
        return $this->render("home.html.twig",[
            'products' => $products
        ]); 
    }  
   
    
}
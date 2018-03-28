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
     */
    public function index(UserRepository $userRepo)
    {
    return $this->render("home.html.twig"); 
    }
}
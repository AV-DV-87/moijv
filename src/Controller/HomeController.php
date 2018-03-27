<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends Controller
{
    /**
     * @Route("/home", name="home")
     */
    public function index(UserRepository $userRepo)
    {
        // Injection de dépendance $userRepo est passé directement en params
        // On n'a pas à l'instancier nous même
        // On utilise un SELECT * FROM gràce à la méthode contenu dans UserRepo  
        $usersList = $userRepo->findAll();
        
        
        return $this->render('home.html.twig',[
            'users'=>$usersList]);
    }
}

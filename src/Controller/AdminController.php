<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends Controller
{
    /**
     * @Route("/admin/dashboard", name="admin_dashboard")
     */
    public function index(UserRepository $userRepo)
    {
        // Injection de dépendance $userRepo est passé directement en params
        // On n'a pas à l'instancier nous même
        // On utilise un SELECT * FROM gràce à la méthode contenu dans UserRepo  
        $usersList = $userRepo->findAll();
        
        
        return $this->render('admin/dashboard.html.twig',[
            'users'=>$usersList]);
    }
}

<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\Common\Persistence\ObjectManager;
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
    /**
     * @Route("/admin/user/delete/{id}", name="delete_user")
     */
    //la route contient l'id qui peut varier et elle sera rechercher
    //automatiquement l'utilisateur correspondant sans find
    public function deleteUser(User $user, ObjectManager $manager)
    {
        $manager->remove($user);
        $manager->flush();
        return $this->redirectToRoute('admin_dashboard');
    }
}

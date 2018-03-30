<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
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
    
    /**
     * @Route("/admin/user/add", name="add_user")
     * @Route("/admin/user/edit/{id}", name="edit_user")
     * 
     */
    //udpate and add function for user
    //condition par défaut null à la création ou mauvais id dans l'url
    //pour activer la première route
    public function editUser(Request $request, ObjectManager $manager, User $user = null)
    {
        //condition pour l'ajout
        if($user === null)
        {
            //attention renvoi des valeurs null pas accépté dans notre entity
            $user = new User();
        }
        $formUser = $this->createForm(UserType::class, $user)
                ->add('Envoyer', SubmitType::class);
        
        //validation et traitement formulaire
        $formUser->handleRequest($request); //déclenche gestion form
        
        if($formUser->isSubmitted() && $formUser->isValid())
        {
            
            $user->setRegisterDate(new \DateTime('now'));
            $user->setRoles('ROLE_USER');
            //persist update and flush it
            $manager->persist($user);
            $manager->flush();
            //return to dashboard
            return $this->redirectToRoute('admin_dashboard');
            
        }
        return $this->render('admin/edit_user.html.twig', [
           'form' => $formUser->createView(), 
        ]);
        
    }
}

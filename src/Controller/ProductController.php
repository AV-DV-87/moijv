<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

//route générale du controller
/**
 * @Route("/product")
 * 
 */
class ProductController extends Controller {

    /**
     * @Route("/", name="product")
     * @Route("/{page}", name="product_paginated", requirements={"page"="\d+"})
     */
    public function index(ProductRepository $productRepo, $page = 1) {

        $productsList = $productRepo->findPaginatedByUser($this->getUser(), $page);

        return $this->render('product/list_product.html.twig', [
                    'products' => $productsList
        ]);
    }

    /**
     * @Route("/delete/{id}", name="delete_product")
     * 
     * 
     */
    public function deleteProduct(Product $product, ObjectManager $manager) {
        //verification que l'utilisateur qui supprime est bien le propriétaire
        if ($product->getOwner()->getId() !== $this->getUser()->getId()) {
            throw $this->createAccessDeniedException('You are not allowed'
                    . 'to delete this produtct');
        }

        $manager->remove($product);
        $manager->flush();
        return $this->redirectToRoute('product');
    }

    /**
     * @Route("/add", name="add_product")
     * @Route("/edit/{id}", name="edit_product")
     * 
     * 
     */
    public function editProduct(Request $request, ObjectManager $manager, Product $product = null) {
        //condition pour l'ajout
        if ($product === null) {
            //attention renvoi des valeurs null pas accépté dans notre entity
            $product = new Product();
            $group = 'insertion';
        }else{
            $oldImage= $product->getImage();
            //transformation dans le format attendu dans le form
            $product->setImage(new File($product->getImage()));
            $group = 'edition';
            
        }
        $formProduct = $this->createForm(ProductType::class, $product, ['validation_groups'=>$group])
                ->add('Envoyer', SubmitType::class);

        //validation et traitement formulaire
        $formProduct->handleRequest($request); //déclenche gestion form

        if ($formProduct->isSubmitted() && $formProduct->isValid()) {
            $product->setOwner($this->getUser());
            //le déplacement du fichier temporaire dans notre fichier upload
            //renommage avec hashage, guessExtension va déduire le type de fichier
            //par le contenu MAIS pour la secu reencodage de l'img normalement
            $image = $product->getImage();            
            if($image === null){
                $product->setImage($oldImage);
            }else{
                $newFileName = md5(uniqid()) . '.' . $image->guessExtension();
                $image->move('uploads', $newFileName);
                $product->setImage('uploads/'.$newFileName);
            }
            //persist update and flush it
            $manager->persist($product);
            $manager->flush();
            //return to dashboard
            return $this->redirectToRoute('product');
        }
        return $this->render('product/edit_product.html.twig', [
                    'form' => $formProduct->createView(),
        ]);
    }

}

<?php

namespace App\Controller;

use App\Entity\Tag;
use App\Repository\ProductRepository;
use App\Repository\TagRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


/**
* @Route("/tag")
*/
class TagController extends Controller
{
    /**
     * @Route("/{slug}/product}", name="tag")
     * @Route("/{slug}/product/{page}", name="tag_paginated")
     */
    public function product(Tag $tag,ProductRepository $productRepo, $page = 1)
    {
        $tagProductList = $productRepo->findPaginatedByTag($tag, $page);
        return $this->render('tag/product.html.twig', [
            'tag' => $tag,
            'products' => $tagProductList
        ]);
    }
    
    /**
     * @Route("", name="search_tag")
     */
    public function search(TagRepository $tagRepo, Request $request)
    {
        //get search
        $search = $request->query->get('search');
        //denied Access if its empty search attempt
        if(! $search){
            throw $this->createNotFoundException();
        }
        
        
        //slug transform
        $slugify= new \Cocur\Slugify\Slugify();
        $slug = $slugify->slugify($search);
        
        //convert to tags array
        $searchedTags = $tagRepo->searchBySlug($slug);
        $formatedTagArray = [];
        
        //Convert in array of array
        foreach ($searchedTags as $tag){
            $formatedTagsArray[] = ['name' => $tag->getName(), 'slug' => $tag->getSlug()];
        }
        return $this->json($formatedTagsArray);
        
    }
    
    
}

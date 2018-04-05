<?php

namespace App\Controller;

use App\Entity\Tag;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
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
}

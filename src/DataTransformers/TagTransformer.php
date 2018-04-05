<?php


namespace App\DataTransformers;

use App\Repository\TagRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Form\DataTransformerInterface;

class TagTransformer implements DataTransformerInterface
{
    
    /**
     *
     * @var TagRepository
     */
    private $tagRepo;
    
    public function __construct(TagRepository $tagRepo)
    {
        $this->tagRepo = $tagRepo;
    }
    
    public function reverseTransform($tagString)
    {
        //array unique pour eviter les doublons
        $tagArray = array_unique(explode(',', $tagString));
        //nouvelle collection
        $tagCollection = new ArrayCollection();
        //control if tags exist and add it to collection  
        foreach($tagArray as $tagName) {
            $tag = $this->tagRepo->getCorrespondingTag($tagName);
            $tagCollection->add($tag);
        }
        return $tagCollection;
    }

    public function transform($tagCollection)
    {
        // array_map (function, array)
        $tagArray = $tagCollection->toArray();
        $nameArray = array_map(function($tag){ return $tag->getName(); }, $tagArray);

        return implode(',', $nameArray);
    }
}                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                
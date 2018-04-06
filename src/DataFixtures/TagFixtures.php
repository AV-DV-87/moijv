<?php

namespace App\DataFixtures;

use App\Entity\Tag;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class TagFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        
        for($i = 0; $i < 40; $i++){
            $tag = new Tag();
            $tag->setName('Tag '.$i);
            $tag->setSlug('tag-'.$i);
            
            //clé de référence pour l'utiliser dans les products fixtures
            $this->addReference('tag'.$i, $tag);
            $manager->persist($tag);
        }
        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }
}

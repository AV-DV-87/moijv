<?php

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class ProductFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $product = new Product();
        
        for($i=0; $i<60; $i++){
            $product = new Product();
            $product->setTitle('title'.$i);
            $product->setDescription('Lorem ipsum dolor sit amet, consectetur adipiscing elit. '
                    . 'Aenean at leo at massa consectetur feugiat at quis lorem. '
                    . 'Donec molestie, augue in lobortis euismod, enim nisl molestie ligula, '
                    . 'eu fringilla.');
            $product->setOwner($this->getReference('user' . rand(0, 59)));
            //url relative qui sera complétée par la méthode asset
            $product->setImage('uploads/500x325.png');
            for($j = 0; $j <rand(0,4); $j++){
                $tag = $this->getReference('tag'.rand(0,39));
                $product->addTag($tag);
            }
            $manager->persist($product);
        }
        $manager->flush();
    }

    public function getDependencies(): array {
        return [
            UserFixtures::class,
            TagFixtures::class
        ];
    }

}

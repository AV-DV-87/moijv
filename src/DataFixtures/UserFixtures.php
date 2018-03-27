<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;


class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);
        for($i=0; $i<60; $i++){
            $user = new User();
            $user->setUsername('user'.$i);
            $user->setPassword('user'.PASSWORD_BCRYPT);
            $user->setEmail('user'.$i.'@fake.fr');
            $user->setRegisterDate(new \DateTime('-'.$i.' days'));
//            demande à doctrine de préparer l'insertion de l'entité en BDD donc
//            donc un array avec toutes les requêtes qui seront exécutés en flush
            $manager->persist($user);
        }
         /**
         * Flushes all changes to objects that have been queued up to now to the database.
         * This effectively synchronizes the in-memory state of managed objects with the
         * database.
         *
         * @return void
         */
        $manager->flush();
    }
}

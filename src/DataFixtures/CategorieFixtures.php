<?php

namespace App\DataFixtures;

use App\Entity\Categorie;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategorieFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // for ($i=1; $i < 10 ; $i++) { 
        //     $categorie = new Categorie();
        //     $categorie->setLibelle("Categorie n°$i")
        //               ->setPosition($i);

        //     $manager->persist($categorie);
        // }

        $manager->flush();
    }
}

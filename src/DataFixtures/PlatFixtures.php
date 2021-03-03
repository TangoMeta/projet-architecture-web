<?php

namespace App\DataFixtures;

use App\Entity\Categorie;
use App\Entity\Plat;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class PlatFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // Créer 3 catégories...
        for ($i=1 ; $i <= 3 ; $i++) { 
            $categorie = new Categorie();
            $categorie->setLibelle("Catégorie $i");

            $manager->persist($categorie);

            // ...dans lesquelles je place 5 plats pour chaque
            for ($j=1; $j <= 3 ; $j++) { 
                $plat = new Plat();
                $k = $i+$j;
                $plat->setLibelle("Plat $k")
                     ->setAllergene(["Allergène 1", "Allergène 2"])
                     ->setImage("https://placehold.it/200x200")
                     ->setIngredients(["Ingrédients 1", "Ingrédient 2", "Ingrédient 3", "Ingrédients 4"])
                     ->setRegime(["Végétarien", "Vegan"])
                     ->setCategorie($categorie);
                
                $manager->persist($plat);
            }
        }
        $manager->flush();
    }
}

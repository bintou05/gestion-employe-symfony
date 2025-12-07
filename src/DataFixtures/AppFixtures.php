<?php

namespace App\DataFixtures;

use App\Entity\Departement;
use App\Entity\Employe;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Création des départements
        $departements = [];
        for ($i = 1; $i <= 5; $i++) {
            $departement = new Departement();
            $departement->setName("Departement $i");

            $manager->persist($departement);
            $departements[] = $departement;
        }

        
        for ($i = 1; $i <= 10; $i++) {
            $employe = new Employe();
            $employe->setName("Employe $i");
            $employe->setCreateAt(new \DateTimeImmutable());
            $employe->setIsActive(true);
            $employe->setUpdateAt(null);

        
            $employe->setDepartement($departements[array_rand($departements)]);

            $manager->persist($employe);
        }

        $manager->flush();
    }
}

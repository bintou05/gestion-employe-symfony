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
        for ($i=0; $i<10; $i++) {
            $departement=new Departement();
            $departement-> setName("Departement".$i);
            #$departement->setCreateAt(new \DateTimeImmutable());
            $departement->setIsActive(true);
            $manager->persist($departement);

            $this->addReference('departement_' . $i, $departement);
            $departements[] = $departement;
        
        }

        $manager->flush();


        $departements=$manager->getRepository(Departement::class)->findAll();
        foreach ($departements as $departement) {
            for ($j=1; $j<=10; $j++) {
                $employe = new Employe();
                $employe->setName("Employe".$j."-".$departement->getName());
                $employe->setTel("77" . str_pad(rand(10000000, 99999999), 8, '0', STR_PAD_LEFT));
                $employe->setSalaire(rand(30000, 75000));
                $employe->setEmbaucheAt(new \DateTimeImmutable());
                $employe->setCreateAt(new \DateTimeImmutable());
                $employe->setIsActive(True);

                $employe->setDepartement($departement);
                $manager->persist($employe);
            }
        }
        $manager->flush();
    }
}

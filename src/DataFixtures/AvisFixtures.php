<?php

namespace App\DataFixtures;

use App\Entity\Avis;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AvisFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i=0; $i < 10; $i++) { 
            $avis = new Avis();
            $avis->setNom('avis n°'.$i)
                ->setDescription('Lorem ipsum dolor sit amet')
                ->setNote(rand(1,5))
                ->setIsactive(rand(0,1) === 1 ? 1 : 0);

            $manager->persist($avis);
            $manager->flush();
        }   
    }
}

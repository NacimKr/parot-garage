<?php

namespace App\DataFixtures;

use App\Entity\Promotion;
use App\Entity\Services;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class PromotionFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $services = $manager->getRepository(Services::class)->findAll();

        for ($i=0; $i < 5; $i++) { 
            $promotion = new Promotion();

            $promotion->setName(sprintf('Promotion n°%d', $i));

            foreach($services as $services){
                $promotion->addService($services[rand(0, count($services)-1)]);
            }

            $promotion->setTauxDeReduction(rand(10, 70));

            $manager->persist($promotion);
            $manager->flush();
        }

    }
}

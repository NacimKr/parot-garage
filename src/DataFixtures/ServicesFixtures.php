<?php

namespace App\DataFixtures;

use App\Entity\Services;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ServicesFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i=0; $i < 10; $i++) { 
            $services = new Services();
            $services->setNom('Services'.$i)
                    ->setPrix(rand(10,500))
                    ->setDescription("Lorem ipsum dolor sit amet");
            $manager->persist($services);
            $manager->flush();
        }
    }
}
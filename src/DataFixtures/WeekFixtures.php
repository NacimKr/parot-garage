<?php

namespace App\DataFixtures;

use App\Entity\Week;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class WeekFixtures extends Fixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $allWeeks = ['Lundi','Mardi','Mercredi','Jeudi','Vendredi','Samedi','Dimanche'];

        for ($i=0; $i < count($allWeeks); $i++) { 
            $week = new Week();
            $week->setName($allWeeks[$i]);

            $manager->persist($week);
        }

        $manager->flush();
    }


    public function getOrder(): int
    {
        return 1; // smaller means sooner
    }
}

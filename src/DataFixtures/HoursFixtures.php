<?php

namespace App\DataFixtures;

use App\Entity\Hours;
use App\Entity\Week;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class HoursFixtures extends Fixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager): void
    {        
        $days = $manager->getRepository(Week::class)->findAll();

        $date = new \DateTime();
        $dt = $date->format('H:i');

        for ($i=0; $i < 7; $i++) {
            if($i < 6){
                $hours = new Hours();
                $hours->setMatinOpen($dt)
                    ->setMatinClose($dt)
                    ->setApremOpen($dt)
                    ->setApremClose($dt)
                    ->setDays($days[$i]);
                if($i === 5){
                    $hours->setApremOpen("Fermé")
                        ->setApremClose("Fermé");
                }
                $manager->persist($hours);
            } 
        }
        
        $manager->flush();
    }

    public function getOrder(): int
    {
        return 2; // smaller means sooner
    }
}

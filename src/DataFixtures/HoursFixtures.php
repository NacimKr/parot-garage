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
        $dt = $date->format('d/m/Y H:i:s');

        for ($i=0; $i < 7; $i++) { 
            $hours = new Hours();
            $hours->setMatinOpen(substr($dt, 10))
                ->setMatinClose(substr($dt, 10))
                ->setApremOpen(substr($dt, 10))
                ->setApremClose(substr($dt, 10))
                ->setDays($days[$i]);
            $manager->persist($hours);
        }
        
        $manager->flush();
    }

    public function getOrder(): int
    {
        return 2; // smaller means sooner
    }
}

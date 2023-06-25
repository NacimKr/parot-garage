<?php

namespace App\DataFixtures;

use App\Entity\Car;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CarFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        for($i=0; $i<10; $i++){
            $car = new Car();

            $car->setMarque('marque'.$i)
                ->setPrix(rand(1000,10000))
                ->setImage('image'.$i)
                ->setKilometrage(rand(10000, 50000))
                ->setAnnee(rand(2000, 2023))
                ->setIsActive(rand(0,1) ? true : false);

            $manager->persist($car);
            $manager->flush();
        }
    }
}

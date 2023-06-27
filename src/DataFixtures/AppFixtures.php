<?php

namespace App\DataFixtures;

use App\Entity\Employee;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{

    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {        
        for ($i=0; $i < 10; $i++) { 
            $employee = new Employee();

            $employee->setEmail('test'.$i.'@gmail.com')
                    ->setName('testName'.$i)
                    ->setFirstName('testFirstName'.$i);
            $hashedPassword = $this->passwordHasher->hashPassword(
                $employee,
                "test123".$i
            );
            $employee->setPassword($hashedPassword)->setRoles($employee->getName() === "testName0" ? ['ROLE_ADMIN'] : ['ROLE_EMPLOYEE']);

            $manager->persist($employee);
            $manager->flush();
        }

    }


    public function getDependencies()
    {
        return [
            WeekFixtures::class,
            PromotionFixtures::class,
        ];
    }
}

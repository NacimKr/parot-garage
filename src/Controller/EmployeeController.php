<?php

namespace App\Controller;

use App\Form\SearchType;
use App\Repository\CarRepository;
use App\Repository\HoursRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/employee')]
class EmployeeController extends AbstractController
{
    private $repositoryHours;

    public function __construct(HoursRepository $repositoryHours)
    {
        $this->repositoryHours = $repositoryHours;
    }

    #[Route('', name: 'app_employee')]
    public function index(CarRepository $carRepository): Response
    {
        $cars = $carRepository->findAll();
        $hours = $this->repositoryHours->findAll();
        return $this->render('employee/index.html.twig', [
            'cars' => $cars
        ]);
    }
}

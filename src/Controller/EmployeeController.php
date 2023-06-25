<?php

namespace App\Controller;

use App\Form\SearchType;
use App\Repository\CarRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/employee')]
class EmployeeController extends AbstractController
{
    #[Route('', name: 'app_employee')]
    public function index(CarRepository $carRepository): Response
    {
        $cars = $carRepository->findAll();
        return $this->render('employee/index.html.twig', [
            'cars' => $cars
        ]);
    }
}

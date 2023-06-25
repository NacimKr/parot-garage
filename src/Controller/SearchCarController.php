<?php

namespace App\Controller;

use App\Repository\CarRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SearchCarController extends AbstractController
{
    #[Route('/search/car', name: 'app_search_car')]
    public function index(CarRepository $carRepository): Response
    {
        $cars = $carRepository->findBy(["isActive" => true]);

        return $this->render('search_car/index.html.twig', [
            'cars' => $cars,
        ]);
    }
}

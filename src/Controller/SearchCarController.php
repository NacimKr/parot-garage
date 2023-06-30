<?php

namespace App\Controller;

use App\Repository\AvisRepository;
use App\Repository\CarRepository;
use App\Repository\HoursRepository;
use App\Repository\ServicesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SearchCarController extends AbstractController
{

    private $repositoryHours;

    public function __construct(HoursRepository $repositoryHours)
    {
        $this->repositoryHours = $repositoryHours;
    }

    #[Route('/search/car', name: 'app_search_car')]
    public function index(
        CarRepository $carRepository, 
        ServicesRepository $servicesRepository,
        AvisRepository $avisRepository,
        Request $request
    ): Response
    {
        //Je recupère les valeurs de mes filtres
        $marque = $request->get('marque');
        $kilometrage = $request->get('kilometrage');
        $prix = $request->get('prix');
        $annee = $request->get('annee');

        $cars = $carRepository->findByCars2($marque = null, $kilometrage, $prix, $annee, 1);

        if($request->get('ajax')){
            return new JsonResponse([
                'content' => $this->renderView('component/_search_filter.html.twig', compact('cars'))
            ]);
        }

        return $this->render('search_car/index.html.twig', compact(
            'cars'
        ));
    }
}

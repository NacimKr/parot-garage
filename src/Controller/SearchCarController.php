<?php

namespace App\Controller;

use Knp\Component\Pager\PaginatorInterface;
use App\Repository\AvisRepository;
use App\Repository\CarRepository;
use App\Repository\HoursRepository;
use App\Repository\ServicesRepository;
use Doctrine\ORM\EntityManagerInterface;
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
        Request $request,
        EntityManagerInterface $em,
        PaginatorInterface $paginator
    ): Response
    {
        //Je recupère les valeurs de mes filtres
        $marque = $request->get('marque');
        $kilometrage = $request->get('kilometrage');
        $prix = $request->get('prix');
        $annee = $request->get('annee');

        $dql   = "SELECT a FROM AcmeMainBundle:Article a";
        $query = $em->createQuery($dql);

        $cars = $paginator->paginate(
            $carRepository->findByCars2($marque, intval($kilometrage), intval($annee), intval($prix)),
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );

        if($request->get('ajax')){
            return new JsonResponse([
                'content' => $this->renderView('component/_search_filter.html.twig', compact('cars')),
                'data' => $cars
            ]);
        }

        $hours = $this->repositoryHours->findAll();

        return $this->render('search_car/index.html.twig', compact(
            'cars', 'hours'
        ));
    }
}

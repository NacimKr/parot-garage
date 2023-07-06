<?php

namespace App\Controller;

use App\Entity\Car;
use App\Form\SearchType;
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
       
    #[Route('/search/car', name: 'app_search_car', methods:['GET','POST'])]
    public function index(
        CarRepository $carRepository, 
        Request $request,
        EntityManagerInterface $em,
        PaginatorInterface $paginator
    ): Response
    {
        //Je recupère les valeurs de mes filtres
        $dql   = "SELECT a FROM AcmeMainBundle:Article a";
        $query = $em->createQuery($dql);

        $marque = $request->get('marque');
        $kilometrageMin = $request->get('kilometrage-min');
        $kilometrageMax = $request->get('kilometrage-max');
        $anneeMin = $request->get('annee-min');
        $anneeMax = $request->get('annee-max');
        $prixMin = $request->get('prix-min');
        $prixMax = $request->get('prix-max');

        $cars = $paginator->paginate(
            $carRepository->findByCars2(
                $marque,
                intval($kilometrageMin),
                intval($kilometrageMax),
                intval($anneeMin),
                intval($anneeMax),
                intval($prixMin),
                intval($prixMax)
            ),
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );

        dump($cars);

        if($request->get('ajax')){
            return new JsonResponse([
                'content' => $this->renderView('component/_search_filter.html.twig', compact('cars')),
                'data' => $cars,
            ]);
        }

        $hours = $this->repositoryHours->findAll();
        
        return $this->render('search_car/index.html.twig', compact(
            'cars', 'hours'
        ));
    }
    
    
    #[Route('/search/car/{id}', name: 'app_search_car_id', methods:['GET'])]
    public function showCar(Car $car)
    {
        return $this->render("search_car/show.html.twig",compact("car"));
    }
}

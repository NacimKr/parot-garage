<?php

namespace App\Controller;

use App\Entity\Avis;
use App\Entity\Car;
use App\Entity\Employee;
use App\Form\AvisType;
use App\Form\CarType;
use App\Form\EmployeeType;
use App\Form\SearchType;
use App\Repository\CarRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\JsonResponse;

class MainController extends AbstractController
{
    #[Route('/', name: 'app_main')]
    public function index(CarRepository $carRepository, Request $request, EntityManagerInterface $em): Response
    {
        //Je recupère les valeurs de mes filtres
        $marque = $request->get('marque');
        $kilometrage = $request->get('kilometrage');
        $prix = $request->get('prix');
        $annee = $request->get('annee');
        // dump($annee);
        
        $cars = $carRepository->findByCars($marque =null, $kilometrage, $annee, $prix);

        if($request->get('ajax')){
            return new JsonResponse([
                'content' => $this->renderView('component/_search_filter.html.twig', compact('cars'))
            ]);
        }

        return $this->render('main/index.html.twig', compact(
            'cars'
        ));
    }


    #[Security("is_granted('ROLE_ADMIN') and is_granted('ROLE_EMPLOYEE')")]
    #[Route('/account/user/{id}', name:"app_account_user")]
    public function accountUser(Employee $employee):Response
    {
        return $this->render('component/_account_user.html.twig', [
            "employee" => $employee
        ]);
    }



    #[Route('/change/visibility/{id}', name: 'app_change_visibility', methods:["GET","POST"])]
    public function changeVisibility(Request $request,CarRepository $carRepository, $id, EntityManagerInterface $em):JsonResponse
    {
        $cars = $carRepository->find($id);
        
        $getValue = json_decode($request->getContent());
        $cars->setIsActive(!$cars->isIsActive());
        $em->persist($cars);
        $em->flush();

        return $this->json([
            "cars" => $cars
        ]);
    }



    #[Route('/login', name: 'app_login')]
    public function login(Request $request): Response
    {
        $form = $this->createForm(EmployeeType::class);
        return $this->render('main/login.html.twig', compact('form'));
    }



    #[Route('/help', name: 'app_help')]
    public function help(): Response
    {
        return $this->render('main/help.html.twig');
    }



    #[Route('/contact', name: 'app_contact')]
    public function contact(): Response
    {
        return $this->render('main/contact.html.twig');
    }



    #[Route('/avis', name: 'app_avis')]
    public function avis(EntityManagerInterface $em, Request $request): Response
    {
        $avis = new Avis();
        $form = $this->createForm(AvisType::class, $avis);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $em->persist($avis);
            $em->flush();
            return $this->redirectToRoute('app_main');
        }

        return $this->render('main/avis.html.twig', compact('form'));
    }
}

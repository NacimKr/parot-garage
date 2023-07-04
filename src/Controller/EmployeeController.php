<?php

namespace App\Controller;

use App\Form\SearchType;
use App\Repository\AvisRepository;
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

    #[Route('/manage/avis', name: 'app_manage_avis')]
    public function manageAvis(AvisRepository $avisRepository): Response
    {
        $avis = $avisRepository->findAll();
        $hours = $this->repositoryHours->findAll();
        
        return $this->render('admin/manage-avis.html.twig', [
            'avis' => $avis,
            "hours" => $hours
        ]);
    }
}

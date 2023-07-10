<?php

namespace App\Controller;

use App\Entity\Avis;
use App\Entity\Car;
use App\Form\CarType;
use App\Form\SearchType;
use App\Repository\AvisRepository;
use App\Repository\CarRepository;
use App\Repository\HoursRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

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


    #[Route('/manage/avis/{id}', name: 'app_manage_avis_modify', methods:["POST"])]
    public function manageAndModifyAvis(EntityManagerInterface $em, Avis $avis)
    {
        $avisChangeActive = $avis->isIsactive();
        $avis = $avis->setIsactive(!$avisChangeActive);
        $em->persist($avis);
        $em->flush();

        return $this->json([
            "avis" => $avis
        ]);
    }


    #[Route('/add/cars', name: 'app_add_cars')]
    public function addCars(Request $request, SluggerInterface $slugger, EntityManagerInterface $em, HoursRepository $hoursRepository): Response
    {
        $cars = new Car();
        $form = $this->createForm(CarType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('image')->getData();
            
            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();
                
                try {
                    $imageFile->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    dump($e->getMessage());
                }
                $cars = $form->getData();
                $cars->setImage($newFilename);

                $em->persist($cars);
                $em->flush();
            }

            $em->persist($cars);
            $em->flush();
            $this->addFlash('success', 'Votre voiture à bien été ajouté');

            return $this->redirectToRoute('app_main');
        }

        $hours = $hoursRepository->findAll();


        return $this->render('admin/add.html.twig', [
            'form' => $form,
            'hours'=> $hours
        ]);
    }



    #[Route('/list/cars', name:"app_list_cars")]
    public function listCars(CarRepository $carRepository, HoursRepository $hoursRepository):Response
    {
        $cars = $carRepository->findAll();
        $hours = $hoursRepository->findAll();
        return $this->render('admin/list-cars.html.twig', compact('cars', 'hours'));
    }



    #[Route('/list/cars/{id}', name:"app_modify_cars")]
    public function modifyCars(Car $cars, Request $request, EntityManagerInterface $em, HoursRepository $hoursRepository):Response
    {
        $form = $this->createForm(CarType::class, $cars);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $em->persist($data);
            $em->flush();
            $this->addFlash('warning', 'Votre voiture à bien été modifié');

            return $this->redirectToRoute('app_main');
        }

        $hours = $hoursRepository->findAll();

        return $this->render('admin/modify-cars.html.twig', compact('cars','form', 'hours'));
    }


    #[Route('/delete/cars/{id}', name:"app_delete_cars")]
    public function deleteCars(Car $car, Request $request, EntityManagerInterface $em):Response
    {
        $em->remove($car);
        $em->flush();
        return $this->redirectToRoute('app_main');

        return $this->render('main/index.html.twig', compact('car','form'));
    }

}

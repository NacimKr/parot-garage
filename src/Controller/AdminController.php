<?php

namespace App\Controller;

use App\Entity\Avis;
use App\Entity\Car;
use App\Entity\Hours;
use App\Entity\Promotion;
use App\Entity\Services;
use App\Entity\Week;
use App\Form\CarType;
use App\Form\EmployeeType;
use App\Form\HoursType;
use App\Form\PromotionType;
use App\Form\ServiceType;
use App\Repository\AvisRepository;
use App\Repository\CarRepository;
use App\Repository\HoursRepository;
use App\Repository\ServicesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/admin')]
#[IsGranted('ROLE_ADMIN')]
class AdminController extends AbstractController
{
    #[Route('/', name: 'app_admin')]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    
    #[Route('/add/cars', name: 'app_add_cars')]
    public function addCars(Request $request, SluggerInterface $slugger, EntityManagerInterface $em): Response
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

            return $this->redirectToRoute('app_main');
        }


        return $this->render('admin/add.html.twig', [
            'form' => $form,
        ]);
    }


    #[Route('/list/cars', name:"app_list_cars")]
    public function listCars(CarRepository $carRepository):Response
    {
        $cars = $carRepository->findAll();
        return $this->render('admin/list-cars.html.twig', compact('cars'));
    }


    #[Route('/list/cars/{id}', name:"app_modify_cars")]
    public function modifyCars(Car $car, Request $request, EntityManagerInterface $em):Response
    {
        $form = $this->createForm(CarType::class, $car);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $em->persist($data);
            $em->flush();
            return $this->redirectToRoute('app_list_cars');
        }

        return $this->render('admin/modify-cars.html.twig', compact('car','form'));
    }


    #[Route('/delete/cars/{id}', name:"app_delete_cars")]
    public function deleteCars(Car $car, Request $request, EntityManagerInterface $em):Response
    {
        $em->remove($car);
        $em->flush();
        return $this->redirectToRoute('app_list_cars');

        return $this->render('admin/modify-cars.html.twig', compact('car','form'));
    }





    #[Route('/list/services', name: 'app_list_services')]
    public function listServices(ServicesRepository $servicesRepository):Response
    {
        $services = $servicesRepository->findAll();
        return $this->render('admin/list-services.html.twig', compact('services'));
    }



    #[Route('/add/services', name: 'app_add_services')]
    public function addServices(EntityManagerInterface $em, Request $request): Response
    {
        $services = new Services();
        $form = $this->createForm(ServiceType::class, $services);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $em->persist($data);
            $em->flush();
            return $this->redirectToRoute('app_main');
        }

        return $this->render('admin/add-services.html.twig', [
            'form' => $form,
        ]);
    }


    #[Route('/list/services/{id}', name:"app_modify_services")]
    public function modifySerices(Services $service, Request $request, EntityManagerInterface $em):Response
    {
        $form = $this->createForm(ServiceType::class, $service);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $em->persist($data);
            $em->flush();
            return $this->redirectToRoute('app_list_services');
        }

        return $this->render('admin/modify-services.html.twig', compact('service','form'));
    }



    #[Route('/delete/services/{id}', name:"app_delete_services")]
    public function deleteServices(Services $service, Request $request, EntityManagerInterface $em):Response
    {
        $em->remove($service);
        $em->flush();
        return $this->redirectToRoute('app_list_services');

        return $this->render('admin/modify-services.html.twig', compact('service','form'));
    }





    #[Route('/manage/avis', name: 'app_manage_avis')]
    public function manageAvis(AvisRepository $avisRepository): Response
    {
        $avis = $avisRepository->findAll();
        return $this->render('admin/manage-avis.html.twig', [
            'avis' => $avis
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



    #[Route('/create/promotion', name: 'app_create_promotion')]
    public function createPromotion(Request $request,EntityManagerInterface $em): Response
    {
        $promotion = new Promotion();
        $form = $this->createForm(PromotionType::class, $promotion);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $em->persist($promotion);
            $em->flush();
            return $this->redirectToRoute('app_main');
        }

        return $this->render('admin/create-promotion.html.twig', compact('form'));
    }


    #[Route('/hours', name: 'app_modify_hours')]
    public function hours(HoursRepository $hoursRepository): Response
    {

        return $this->render('admin/modify-hours.html.twig',[
            "hours" => $hoursRepository->findAll()
        ]);
    }


    #[Route('/hours/{id}', name: 'app_modify_hours_2')]
    public function modifyHours(Hours $hours, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(HoursType::class, $hours);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $em->persist($hours);
            $em->flush();
            return $this->redirectToRoute('app_modify_hours');
        }

        return $this->render('admin/form-hours.html.twig',[
            "hours" => $hours,
            "form" => $form
        ]);
    }


    #[Route('/create/account', name: 'app_create_account')]
    public function createAccount(): Response
    {
        $form = $this->createForm(EmployeeType::class);

        return $this->render('admin/create-account.html.twig', [
            'form' => $form,
        ]);
    }
}

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
use Knp\Component\Pager\PaginatorInterface;
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
        return $this->render('admin/index.html.twig');
    }

    
    #[Route('/add/cars', name: 'app_add_cars_admin')]
    public function addCars(Request $request, SluggerInterface $slugger, EntityManagerInterface $em, HoursRepository $hoursRepository): Response
    {
        $cars = new Car();
        $form = $this->createForm(CarType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $cars->isIsActive(true);
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
            // dd($cars);
            $this->addFlash('success', 'Votre voiture à bien été ajouté');

            return $this->redirectToRoute('app_main');
        }

        $hours = $hoursRepository->findAll();


        return $this->render('admin/add.html.twig', [
            'form' => $form,
            'hours'=> $hours
        ]);
    }


    #[Route('/list/cars', name:"app_list_cars_admin")]
    public function listCars(
        CarRepository $carRepository,
        Request $request, 
        HoursRepository $hoursRepository,
        PaginatorInterface $paginator,
        EntityManagerInterface $em
        ):Response
    {

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
            $carRepository->findByCarsAdmin(
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
        $hours = $hoursRepository->findAll();
        return $this->render('admin/list-cars.html.twig', compact('cars', 'hours'));
    }


    #[Route('/list/cars/{id}', name:"app_modify_cars_admin")]
    public function modifyCars(Car $car, Request $request, EntityManagerInterface $em, HoursRepository $hoursRepository):Response
    {
        $form = $this->createForm(CarType::class, $car);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $em->persist($data);
            $em->flush();
            $this->addFlash('warning', 'Votre voiture à bien été modifié');
            return $this->redirectToRoute('app_list_cars_admin');
        }

        $hours = $hoursRepository->findAll();

        return $this->render('admin/modify-cars.html.twig', compact('car','form', 'hours'));
    }


    #[Route('/delete/cars/{id}', name:"app_delete_cars_admin")]
    public function deleteCars(Car $car, Request $request, EntityManagerInterface $em):Response
    {
        $em->remove($car);
        $em->flush();
        $this->addFlash('danger', 'Votre voitures à bien été supprimé');
        return $this->redirectToRoute('app_list_cars_admin');

        return $this->render('admin/modify-cars.html.twig', compact('car','form'));
    }





    #[Route('/list/services', name: 'app_list_services')]
    public function listServices(ServicesRepository $servicesRepository, HoursRepository $hoursRepository):Response
    {
        $services = $servicesRepository->findAll();
        $hours = $hoursRepository->findAll();
        return $this->render('admin/list-services.html.twig', compact('services', 'hours'));
    }



    #[Route('/add/services', name: 'app_add_services')]
    public function addServices(EntityManagerInterface $em, Request $request, HoursRepository $hoursRepository): Response
    {
        $services = new Services();
        $form = $this->createForm(ServiceType::class, $services);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $em->persist($data);
            $em->flush();
            $this->addFlash('success', 'Votre nouveau service à bien été ajouté');
            return $this->redirectToRoute('app_main');
        }

        $hours = $hoursRepository->findAll();

        return $this->render('admin/add-services.html.twig', [
            'form' => $form,
            'hours' => $hours
        ]);
    }


    #[Route('/list/services/{id}', name:"app_modify_services")]
    public function modifySerices(Services $service, Request $request, EntityManagerInterface $em, HoursRepository $hoursRepository):Response
    {
        $form = $this->createForm(ServiceType::class, $service);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $em->persist($data);
            $em->flush();
            $this->addFlash('warning', 'Service à bien été modifié');
            return $this->redirectToRoute('app_list_services');
        }

        $hours = $hoursRepository->findAll();

        return $this->render('admin/modify-services.html.twig', compact('service','form', 'hours'));
    }



    #[Route('/delete/services/{id}', name:"app_delete_services")]
    public function deleteServices(Services $service, Request $request, EntityManagerInterface $em):Response
    {
        $em->remove($service);
        $em->flush();
        return $this->redirectToRoute('app_list_services');

        return $this->render('admin/modify-services.html.twig', compact('service','form'));
    }


    // #[Route('/manage/avis/{id}', name: 'app_manage_avis_modify', methods:["POST"])]
    // public function manageAndModifyAvis(EntityManagerInterface $em, Avis $avis)
    // {
    //     $avisChangeActive = $avis->isIsactive();
    //     $avis = $avis->setIsactive(!$avisChangeActive);
    //     $em->persist($avis);
    //     $em->flush();

    //     return $this->json([
    //         "avis" => $avis
    //     ]);
    // }


    #[Route('/hours', name: 'app_modify_hours')]
    public function hours(HoursRepository $hoursRepository): Response
    {

        return $this->render('admin/modify-hours.html.twig',[
            "hours" => $hoursRepository->findAll()
        ]);
    }


    #[Route('/hours/{id}', name: 'app_modify_hours_2')]
    public function modifyHours(Hours $hours, Request $request, EntityManagerInterface $em, HoursRepository $hoursRepository): Response
    {
        $form = $this->createForm(HoursType::class, $hours);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $em->persist($hours);
            $em->flush();
            $this->addFlash('warning', 'Horaires à bien été modifié');
            return $this->redirectToRoute('app_modify_hours');
        }
        $hours = $hoursRepository->findAll();
        
        return $this->render('admin/form-hours.html.twig',[
            "hours" => $hours,
            "form" => $form
        ]);
    }


    #[Route('/create/account', name: 'app_create_account')]
    public function createAccount(HoursRepository $hoursRepository): Response
    {
        $form = $this->createForm(EmployeeType::class);
        $hours = $hoursRepository->findAll();

        return $this->render('admin/create-account.html.twig', [
            'form' => $form,
            'hours' => $hours
        ]);
    }
}

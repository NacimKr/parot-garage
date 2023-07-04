<?php

namespace App\Controller;

use App\Entity\Avis;
use App\Entity\Car;
use App\Entity\Contact;
use App\Entity\Employee;
use App\Form\AvisType;
use App\Form\CarType;
use App\Form\ContactType;
use App\Form\EmployeeType;
use App\Trait\traitHours;
use App\Form\SearchType;
use App\Repository\AvisRepository;
use App\Repository\CarRepository;
use App\Repository\HoursRepository;
use App\Repository\ServicesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;

class MainController extends AbstractController
{

    private $repositoryHours;

    public function __construct(HoursRepository $repositoryHours)
    {
        $this->repositoryHours = $repositoryHours;
    }

    #[Route('/', name: 'app_main')]
    public function index(
        CarRepository $carRepository, 
        Request $request, 
        EntityManagerInterface $em,
        ServicesRepository $servicesRepository,
        AvisRepository $avisRepository,
        HoursRepository $hoursRepository
        ): Response
    {
        //Je recupère les valeurs de mes filtres
        $marque = $request->get('marque');
        $kilometrage = $request->get('kilometrage');
        $prix = $request->get('prix');
        $annee = $request->get('annee');
        // dump($annee);
        
        $hours = $this->repositoryHours->findAll();
        $services = $servicesRepository->findAll();
        $avis = $avisRepository->findBy(['isactive' => true]);
        $cars = $carRepository->findByCars2($marque, $kilometrage, $annee, $prix);

        if($request->get('ajax')){
            return new JsonResponse([
                'content' => $this->renderView('component/_search_filter.html.twig', compact('cars'))
            ]);
        }

        return $this->render('main/index.html.twig', compact(
            'cars', "hours", "services", "avis"
        ));
    }


    #[Security("is_granted('ROLE_ADMIN') and is_granted('ROLE_EMPLOYEE')")]
    #[Route('/account/user/{id}', name:"app_account_user")]
    public function accountUser(Employee $employee):Response
    {
        $hours = $this->repositoryHours->findAll();
        return $this->render('component/_account_user.html.twig', [
            "employee" => $employee
        ]);
    }



    #[Route('/change/visibility/{id}', name: 'app_change_visibility', methods:["GET","POST"])]
    public function changeVisibility(
        Request $request,
        CarRepository $carRepository, 
        ServicesRepository $servicesRepository,
        AvisRepository $avisRepository,
        HoursRepository $hoursRepository,
        $id, 
        EntityManagerInterface $em)
    {
        $cars = $carRepository->find($id);
        $hours = $this->repositoryHours->findAll();
        $getValue = json_decode($request->getContent());

                
        $hours = $this->repositoryHours->findAll();
        $services = $servicesRepository->findAll();
        $avis = $avisRepository->findBy(['isactive' => true]);
        $cars->setIsActive(!$cars->isIsActive());

        $em->persist($cars);
        $em->flush();

        return $this->json([
            "cars" => $cars
        ]);
    }



    #[Route('/login', name: 'app_login')]
    public function login(Request $request, HoursRepository $hoursRepository): Response
    {
        $hours = $this->repositoryHours->findAll();
        $form = $this->createForm(EmployeeType::class);
        $hours = traitHours::getHours($hoursRepository);
        return $this->render('main/login.html.twig', compact('form','hours'));
    }



    #[Route('/help', name: 'app_help')]
    public function help(HoursRepository $hoursRepository): Response
    {
        $hours = $this->repositoryHours->findAll();
        return $this->render('main/help.html.twig');
    }



    #[Route('/contact', name: 'app_contact')]
    public function contact(
        Request $request, 
        EntityManagerInterface $em, 
        HoursRepository $hoursRepository,
        MailerInterface $mailer
    ): Response
    {
        $contact = new Contact();

        $form = $this->createForm(ContactType::class, $contact);
        $hours = $hoursRepository->findAll();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $em->persist($contact);
            $em->flush();
            //Email a envoyé
            $email = (new TemplatedEmail())
                ->from(!$contact->getEmail() ? "fabien@example.com" : $contact->getEmail())
                ->to('info-contact@garage-parot.com')
                ->subject('Info Email')
                ->htmlTemplate('email/email.html.twig')
                
                // pass variables (name => value) to the template
                ->context([
                    'expiration_date' => new \DateTime('+7 days'),
                    'contact' => $contact
                ]);
    
            $mailer->send($email);
            
            return $this->redirectToRoute('app_main');
        }

        

        $hours = $this->repositoryHours->findAll();
        return $this->render('main/contact.html.twig', compact('form', "hours"));
    }

    #[Route('/avis', name: 'app_avis')]
    public function avis(EntityManagerInterface $em, Request $request): Response
    {
        $avis = new Avis();
        $form = $this->createForm(AvisType::class, $avis);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $avis->setIsactive(false);
            $em->persist($avis);
            $em->flush();
            return $this->redirectToRoute('app_main');
        }
        $hours = $this->repositoryHours->findAll();

        return $this->render('main/avis.html.twig', compact('form', 'hours'));
    }
}

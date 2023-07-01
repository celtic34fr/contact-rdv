<?php

namespace Celtic34fr\ContactRendezVous\Controller\Backend;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/customer_events', name: 'customer-evts')]
class CustomerEventsController extends AbstractController
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
        # code...
    }

    #[Route('/new-meeting/{customer}-{contact}', name: 'new-meeting')]
    public function index(): Response
    {
        return $this->render('@contact-rendezvous/customer-events/new-meeting.html.twig', [
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/CustomerEventsController.php',
        ]);
    }
}

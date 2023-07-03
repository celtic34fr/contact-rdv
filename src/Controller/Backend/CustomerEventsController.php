<?php

namespace Celtic34fr\ContactRendezVous\Controller\Backend;

use Exception;
use Doctrine\ORM\EntityManagerInterface;
use Celtic34fr\ContactCore\Entity\CliInfos;
use Celtic34fr\ContactGestion\Entity\Contact;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Celtic34fr\ContactRendezVous\Entity\CalEvent;
use Celtic34fr\ContactCore\Repository\CliInfosRepository;
use Celtic34fr\ContactGestion\Repository\ContactRepository;
use Celtic34fr\ContactRendezVous\Repository\CalEventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/customer_evts', name: 'custevts-')]
class CustomerEventsController extends AbstractController
{
    private CliInfosRepository $customerRepo;
    private ContactRepository $contactRepo;
    private CalEventRepository $eventRepo;

    public function __construct(private EntityManagerInterface $entityManager)
    {
        $this->customerRepo     = $entityManager->getRepository(CliInfos::class);
        $this->contactRepo      = $entityManager->getRepository(Contact::class);
        $this->eventsRepo       = $entityManager->getRepository(CalEvent::class);
    }

    #[Route('/new-meeting/{customer}-{contact}', name: 'new-meeting')]
    public function index(Request $request, int $customer, int $contact): Response
    {
        if ($customer > 0) {
            $customer = $this->entityManager->getRepository(CliInfos::class)->find($customer);
        }
        if ($contact > 0) {
            $contact = $this->entityManager->getRepository(Contact::class)->find($contact);
        }

        if (!$customer || !$contact) {
            throw new Exception("demandeur ou demande de contact introuvable, veuillez en avertir l'admistrateur");
        } elseif ($customer != $contact->getClient()) {
            throw new Exception('Demandeur {custormer->getFullname()} incompatible avec la demande de contact (ID: [$contact->getId()}');
        }

        $events = $this->eventRepo->findAllPaginateFromDate(1, 10, null, "json");

        return $this->render('@contact-rdv/customer-events/new-meeting.html.twig', [
            'customer' => $customer,
            'contact' => $contact,
            'events' => $events,
        ]);
    }
}

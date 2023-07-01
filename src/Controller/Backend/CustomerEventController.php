<?php

namespace Celtic34fr\ContactRendezVous\Controller\Backend;

use Celtic34fr\ContactCore\Entity\CliInfos;
use Celtic34fr\ContactGestion\Entity\Contact;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

#[Route('customer_evts', name: 'custevts-')]
class CustomerEventController extends AbstractController
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
        # code...
    }

    #[Route('/new-meeting/{customer}-{contact}', name: 'new-meeting')]
    /**
     * Aide à la génération d'un courriel de proposition de rendez-vous ou invitation à prise directe de rendez-vous
     */
    public function newCostomerMeeting(Request $request, int $customer, int $contact): Response
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

        $this->render("@contact-rdv/customer-event/new-meeting.html.twig");
    }
}

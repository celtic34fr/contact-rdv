<?php

namespace Celtic34fr\ContactRendezVous\Controller\Backend;

use Celtic34fr\CalendarCore\Enum\StatusEnums;
use Celtic34fr\ContactCore\Entity\CliInfos;
use Celtic34fr\ContactCore\Entity\Parameter;
use Celtic34fr\ContactCore\Repository\CliInfosRepository;
use Celtic34fr\ContactGestion\Entity\Contact;
use Celtic34fr\ContactGestion\Repository\ContactRepository;
use Celtic34fr\ContactRendezVous\Entity\EventRdv;
use Celtic34fr\ContactRendezVous\Enum\EventEnums;
use Celtic34fr\ContactRendezVous\Form\CalEventType;
use Celtic34fr\ContactRendezVous\FormEntity\CalEventFE;
use Celtic34fr\ContactRendezVous\Repository\EventRdvRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/customer_evts', name: 'custevts-')]
class CustomerEventsController extends AbstractController
{
    const PARAM_CLE = "SysCalNature"; // Clé de la liste des type d'événement dans Parameters

    private CliInfosRepository $customerRepo;
    private ContactRepository $contactRepo;
    private EventRdvRepository $eventRepo;

    public function __construct(private EntityManagerInterface $entityManager)
    {
        $this->customerRepo     = $entityManager->getRepository(CliInfos::class);
        $this->contactRepo      = $entityManager->getRepository(Contact::class);
        $this->eventRepo        = $entityManager->getRepository(EventRdv::class);
    }

    #[Route('/new-meeting/{customer}-{contact}', name: 'new-meeting')]
    public function index(Request $request, int $customer, int $contact): Response
    {
        $customer = ($customer > 0) ? $this->customerRepo->find($customer) : null;
        $contact = ($contact > 0) ? $this->contactRepo->find($contact) : null;
        if (!$customer || !$contact) {
            throw new Exception("demandeur ou demande de contact introuvable, veuillez en avertir l'admistrateur");
        } elseif ($customer != $contact->getClient()) {
            throw new Exception('Demandeur {custormer->getFullname()} incompatible avec la demande de contact (ID: [$contact->getId()}');
        }

        $events = $this->eventRepo->findAllPaginateFromDate(1, 10, null, "json");
        $event = new CalEventFE();
        $event->setCustomerId($customer->getId());
        $event->setContactId($contact->getId());
        $event->setNature(EventEnums::ContactTel->_toString());
        $form = $this->createForm(CalEventType::class, $event);
        $date_min = (new DateTime('now'))->modify("+1 day")->format("d/m/Y H:i:s");

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // traitement du formulaire pour ajouter un rendez-vous à CalEvents nature : CTEL en temporaire
            $eventRdv = new EventRdv();
            $eventRdv->setInvite($customer);
            $natureCTEL = $this->entityManager->getRepository(Parameter::class)->findByPartialFields([
                'cle' => self::PARAM_CLE,
                'value' => 'CTEL'
            ]);
            $eventRdv->setNature($natureCTEL);
            $eventRdv->setStartAt($event->getTimeAt());
            $eventRdv->setDuration("P1h");
            $eventRdv->setStatus(StatusEnums::NeedsAction->_toString());
            $eventRdv->setSubject($event->getObjet());
            $eventRdv->setDetails($event->getComplements());

            // génération d'un courriel de proposition du RDV téléphonique
            // -> reprise date, heure et objet rendez-vous
            // -> ajout de 2 liens : 
            //      => un pour confirmer le RDV Téléphonique
            //      => un pour que le demandeur puisse prendre directement le dit RDV (annulant le précédent)
        }

        return $this->render('@contact-rdv/customer-events/new-meeting.html.twig', [
            'customer' => $customer,
            'contact' => $contact,
            'events' => $events,
            'form' => $form->createView(),
            'date_min' => $date_min,
            'date_time' => null,
        ]);
    }
}

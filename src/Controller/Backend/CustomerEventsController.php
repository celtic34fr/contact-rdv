<?php

namespace Celtic34fr\ContactRendezVous\Controller\Backend;

use Celtic34fr\CalendarCore\Enum\StatusEnums;
use Celtic34fr\CalendarCore\Repository\ParameterRepository;
use Celtic34fr\ContactCore\Entity\CliInfos;
use Celtic34fr\ContactCore\Entity\Parameter;
use Celtic34fr\ContactCore\Repository\CliInfosRepository;
use Celtic34fr\ContactCore\Service\ExtensionConfig;
use Celtic34fr\ContactCore\Service\SendMailer;
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
use Twig\Environment;

#[Route('/customer_evts', name: 'custevts-')]
class CustomerEventsController extends AbstractController
{
    const PARAM_CLE = "SysCalNature"; // Clé de la liste des type d'événement dans Parameters

    private CliInfosRepository $customerRepo;
    private ContactRepository $contactRepo;
    private EventRdvRepository $eventRdvRepo;
    private ParameterRepository $parameterRepo;

    public function __construct(private EntityManagerInterface $entityManager, private Environment $twigEnvironment,
    private ExtensionConfig $extConfig)
    {
        $this->customerRepo     = $entityManager->getRepository(CliInfos::class);
        $this->contactRepo      = $entityManager->getRepository(Contact::class);
        $this->eventRdvRepo     = $entityManager->getRepository(EventRdv::class);
        $this->parameterRepo    = $entityManager->getRepository(Parameter::class);
    }

    #[Route('/new-meeting/{contact}-{demand}', name: 'new-meeting')]
    /**
     * @param Request $request
     * @param integer $contact
     * @param integer $demand
     * @return Response
     */
    public function index(Request $request, int $contact, int $demand): Response
    {
        $contact = ($contact > 0) ? $this->customerRepo->find($contact) : null;
        $demand = ($demand > 0) ? $this->contactRepo->find($demand) : null;
        if (!$contact || !$demand) {
            throw new Exception("demandeur ou demande de contact introuvable, veuillez en avertir l'admistrateur");
        } elseif ($contact != $demand->getClient()) {
            throw new Exception('Demandeur {contact->getFullname()} incompatible avec la demande de contact (ID: [$demand->getId()}');
        }

        $events = $this->eventRdvRepo->findAllPaginateFromDate(1, 10, null, "json");
        $event = new CalEventFE();
        $event->setCustomerId($contact->getId());
        $event->setContactId($demand->getId());
        $event->setNature(EventEnums::ContactTel->_toString());
        $form = $this->createForm(CalEventType::class, $event);
        $date_min = (new DateTime('now'))->modify("+1 day")->format("d/m/Y H:i:s");

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // traitement du formulaire pour ajouter un rendez-vous à CalEvents nature : CTEL en temporaire
            $eventRdv = new EventRdv();
            $eventRdv->setInvite($contact);
            $natureCTEL = $this->parameterRepo->findByPartialFields([
                'cle' => self::PARAM_CLE,
                'value' => 'CTEL'
            ]);
            $eventRdv->setNature($natureCTEL);
            $eventRdv->setStartAt($event->getTimeAt());
            $eventRdv->setDuration("P1h");
            $eventRdv->setStatus(StatusEnums::NeedsAction->_toString());
            $eventRdv->setSubject($event->getObjet());
            $eventRdv->setDetails($event->getComplements());
            $this->eventRdvRepo->save($eventRdv, true);

            // génération d'un courriel de proposition du RDV téléphonique
            // -> reprise date, heure et objet rendez-vous
            // -> ajout de 2 liens : 
            //      => un pour confirmer le RDV Téléphonique
            //      => un pour que le demandeur puisse prendre directement le dit RDV (annulant le précédent)
            $connexion = [];
            $bodyContext = [
                'client' => $contact,
                'demande' => $demand,
                'rdv' => $eventRdv,
            ];
            $mailer = new SendMailer();
            $mailer->initialize($this->entityManager, $this->twigEnvironment, $this->extConfig);
            $rslt = $mailer->sendTemplate(
                $contact, '@contact-rdv/courriels/send_proposition.html.twig',
                $eventRdv->getSubject(), $bodyContext
            );
        }

        return $this->render('@contact-rdv/customer-events/new-meeting.html.twig', [
            'customer' => $contact,
            'contact' => $demand,
            'events' => $events,
            'form' => $form->createView(),
            'date_min' => $date_min,
            'date_time' => null,
        ]);
    }

    #[Route('/accept_rdv/{contact}-{eventRdv}', name: 'new-meeting')]
    /**
     * @param Request $request
     * @param integer $contact
     * @param integer $eventRdv
     * @return void
     */
    public function accept_rdv(Request $request, int $contact, int $eventRdv)
    {
        $contact = ($contact > 0) ? $this->customerRepo->find($contact) : null;
        $eventRdv = ($eventRdv > 0) ? $this->eventRdvRepo->find($eventRdv) : null;

        if (!$contact || !$eventRdv) {
            throw new Exception('Bad request');
        }

        /** RDV proposition accepted by contact */
        $eventRdv->setStatus(StatusEnums::Accepted->_toString());
        $this->entityManager->flush();
        return $this->render('@contact-rdv/customer-events/accepted_rdv.html.twig', [
            'contact' => $contact,
            'eventRdv' => $eventRdv,
        ]);
    }

    #[Route('/report_rdv/{contact}-{eventRdv}', name: 'new-meeting')]
    /**
     * @param Request $request
     * @param integer $contact
     * @param integer $eventRdv
     * @return void
     */
    public function report_rdv(Request $request, int $contact, int $eventRdv)
    {
        $contact = ($contact > 0) ? $this->customerRepo->find($contact) : null;
        $eventRdv = ($eventRdv > 0) ? $this->eventRdvRepo->find($eventRdv) : null;


        if ($contact && $eventRdv) {
            /** contact demand to report its RDV */
        } else {
            throw new Exception('Bad request');
        }
    }
}

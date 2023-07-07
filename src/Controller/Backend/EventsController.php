<?php

namespace Celtic34fr\ContactRendezVous\Controller\Backend;

use Celtic34fr\ContactCore\Traits\Utilities;
use Celtic34fr\ContactRendezVous\Entity\RendezVous;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

#[Route('events', name: 'evt-')]
class EventsController extends AbstractController
{
    use Utilities;
    
    private EntityManagerInterface $entityManager;
    protected $container;

    public function __construct(EntityManagerInterface $entityManager, ContainerInterface $container)
    {
        $this->entityManager = $entityManager;
        $this->container = $container;
    }

    #[Route('/list/{currentPage}', name: 'list')]
    /**
     * interface pour afficher les requêtes adressées par les internautes
     * @param int $currentPage
     */
    public function index($currentPage = 1): Response
    {
        $events = [];
        $dbPrefix = $this->getParameter('bolt.table_prefix');

        if ($this->existsTable($dbPrefix.'rendezvous') == true) {
            $events = $this->entityManager->getRepository(RendezVous::class)
                ->findEventsAll($currentPage);
            /**
             * avoir une case à cocher pour montrer les demandes déjà traitées
             * module de recherche dans les requêtes : date (format français), nom de l'internaute, sujet
             *    en saisie totale comme partielle.
             */
        } else {
            $this->addFlash('danger', "La table RendezVous n'existe pas, veuillez en avertir l'administrateur");
        }
        return $this->render('@contact-rdv/events/index.html.twig', [
            'events' => $events['datas'] ?? [],
            'currentPage' => $events['page'] ?? 0,
            'pages' => $events['pages'] ?? 0,
        ]);
    }

    #[Route('/input', name: 'input')]
    /**
     * interface pour afficher les requêtes adressées par les internautes
     * @param int $currentPage
     */
    public function input($currentPage = 1)
    {

    }

    #[Route('/evt_type_gest', name: 'evt-type-gest')]
    /**
     * interface pour gérer les types d'évènement dans le calendrier
     * @param int $currentPage
     */
    public function evt_type_gest($currentPage = 1)
    {

    }
}
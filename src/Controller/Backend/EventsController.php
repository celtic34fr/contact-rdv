<?php

namespace Celtic34fr\ContactRendezVous\Controller\Backend;

use Celtic34fr\ContactCore\Service\Utilities;
use Celtic34fr\ContactRendezVous\Entity\RendezVous;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

#[Route('events')]
class EventsController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    protected $container;

    public function __construct(EntityManagerInterface $entityManager, ContainerInterface $container)
    {
        $this->entityManager = $entityManager;
        $this->container = $container;
    }

    #[Route('/list/{currentPage}', name: 'evt_list')]
    /**
     * interface pour afficher les requêtes adressées par les internautes
     * @param Utilities $utility
     * @param int $currentPage
     */
    public function index(Utilities $utility, $currentPage = 1): Response
    {
        $events = [];
        $dbPrefix = $this->getParameter('bolt.table_prefix');

        if ($utility->existsTable($dbPrefix.'rendezvous') == true) {
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

    #[Route('/input', name: 'evt_input')]
    /**
     * interface pour afficher les requêtes adressées par les internautes
     * @param Utilities $utility
     * @param int $currentPage
     */
    public function input(Utilities $utility, $currentPage = 1)
    {

    }
}
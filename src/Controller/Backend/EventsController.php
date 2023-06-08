<?php

use Celtic34fr\ContactCore\Service\Utilities;
use Celtic34fr\ContactRendezVous\Entity\RendezVous;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

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

    #[Route('/events/{display_mode}-{year}-{month}-{week}-{day}', name: 'next_events')]
    /**
     * interface pour afficher les requêtes adressées par les internautes
     * @param Utilities $utility
     * @param int $currentPage
     */
    public function index(
        Utilities $utility,
        $display_mode = "m",
        $year = null,
        $month = null,
        $week = null,
        $day = null
    ): Response {
        $events = [];
        $dbPrefix = $this->getParameter('bolt.table_prefix');

        if ($utility->existsTable($dbPrefix . 'rendezvous') == true) {
            /** traitement de la date en paramètre de la route */
            if ($year && !$month) {
                $month = 1;
            }
            if (!$year) {
                $year = (new DateTime('now'))->format('Y');
            }
            if (!$month) {
                $month = (new DateTime('now'))->format('m');
            }
            if ($display_mode === "w" && !$week) {
                $today = (new DateTime('now'))->format("Y-m-d");
                $today = explode('-', $today);
                $mktime = mktime(0, 0, 0, $today[2], $today[1], $today[0]);
                $week = (int) date("W", $mktime);
            }
            if ($display_mode === "d" && !$day) {
                $month = (new DateTime('now'))->format('d');
            }

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

<?php

use Celtic34fr\ContactCore\Traits\Utilities;
use Celtic34fr\ContactRendezVous\Entity\RendezVous;
use Celtic34fr\ContactRendezVous\Repository\RendezVousRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('events')]
class EventsController extends AbstractController
{
    private RendezVousRepository $rendezVousRepo;

    public function __construct(private EntityManagerInterface $entityManager, private ContainerInterface $container)
    {
        $this->entityManager = $entityManager;
        $this->container = $container;
        $this->rendezVousRepo = $entityManager->getRepository(RendezVous::class);
    }

    #[Route('/events/{display_mode}-{base_day}/{current_page}-{plimit}', name: 'next_events')]
    /**
     * interface pour afficher les requêtes adressées par les internautes
     * @param Utilities $utility
     * @param int $currentPage
     */
    public function index(
        Utilities $utility,
        $display_mode = "m",
        $base_day = null,
        $currentPage = 1,
        $plimit = 10
    ): Response {
        $events = [];
        $dbPrefix = $this->getParameter('bolt.table_prefix');

        if ($utility->existsTable($dbPrefix . 'rendezvous') == true) {
            if (!$base_day) {
                $date_ref = new DateTime('now');
            } else {
                /** $base_date : chaine au format SSAAMMJJ */
                $base_date = substr($base_day, 0, 4) . '-' . substr($base_day, 4, 2) . '-' . substr($base_day, 6);
                $date_ref = new DateTime($base_date);
            }

            $start_year = (int) $date_ref->format("Y");
            $start_month = (int) $date_ref->format('m');
            $start_day = (int) $date_ref->format("d");
            $date = mktime(
                0,
                0,
                0,
                $start_day,
                $start_month,
                $start_year
            );
            $start_week = (int)date('W', $date);

            /** $display_mode : 
             *      'm'     mois de $base_day,
             *      'w'     semaine de,$base_day
             *      'd'     jour de base_day
             */
            switch ($display_mode) {
                case 'y':
                case 'm':
                case 'd':
                    continue;
                    break;
                default:
                    throw new Exception("mode affichage $display_mode non traité");
                    break;
            }

            $events = $this->rendezVousRepo->findEventsAllFromDate($currentPage, $plimit, $date_ref);
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
            'locale' => 'fr',
            'initialView' => 'dayGridMonth',
            'date_ref' => $date_ref,
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

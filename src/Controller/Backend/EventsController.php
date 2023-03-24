<?php

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
    private Environment $twigEnvironment;
    protected $container;

    public function __construct(EntityManagerInterface $entityManager, Environment $twigEnvironment,
                                ContainerInterface $container)
    {
        $this->entityManager = $entityManager;
        $this->twigEnvironment = $twigEnvironment;
        $this->container = $container;
    }

    #[Route('/list/{currentPage}', name: 'evt_list')]
    /**
     * interface pour afficher les requêtes adressées par les internautes
     * @param Utilities $utility
     * @param int $currentPage
     */
    public function index(Utilities $utility, $currentPage = 1)
    {
        $events = [];
        $dbPrefix = $this->getParameter('bolt.table_prefix');

        if ($utility->existsTable($dbPrefix.'rendezvous') == true) {
            $requests = $this->entityManager->getRepository(RendezVous::class)
                ->findRequestAll($currentPage);
            /**
             * avoir une case à cocher pour montrer les demandes déjà traitées
             * module de recherche dans les requêtes : date (format français), nom de l'internaute, sujet
             *    en saisie totale comme partielle.
             */
        } else {
            $this->addFlash('danger', "La table Demandes n'existe pas, veuillez en avertir l'administrateur");
        }
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
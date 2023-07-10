<?php

namespace Celtic34fr\ContactRendezVous\Controller\Backend;

use DateTimeImmutable;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Celtic34fr\ContactCore\Entity\Parameter;
use Celtic34fr\ContactCore\Traits\Utilities;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Celtic34fr\ContactRendezVous\Entity\RendezVous;
use Celtic34fr\ContactRendezVous\Entity\ParamsCalNature;
use Celtic34fr\ContactRendezVous\Form\CalCategoriesType;
use Celtic34fr\ContactRendezVous\FormEntity\CalCategoryFE;
use Celtic34fr\ContactRendezVous\FormEntity\CalCategoriesFE;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('events', name: 'evt-')]
class EventsController extends AbstractController
{
    use Utilities;

    const PARAM_CLE = "calNature";
    
    private EntityManagerInterface $entityManager;
    protected $container;
    private $schemaManager;
    private EntityRepository $paramRepo;

    public function __construct(EntityManagerInterface $entityManager, ContainerInterface $container)
    {
        $this->entityManager = $entityManager;
        $this->schemaManager = $entityManager->getConnection()->getSchemaManager();
        $this->container = $container;
        $this->paramRepo = $entityManager->getRepository(Parameter::class);
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

    #[Route('/evt_type_gest', name: 'type-gest')]
    /**
     * interface pour gérer les types d'évènement dans le calendrier
     */
    public function evt_type_gest(Request $request)
    {
        $catagories = [];
        $dbPrefix = $this->getParameter('bolt.table_prefix');
        $context = [];
        $reorgList = false;

        if ($this->existsTable($dbPrefix.'parameters') == true) {
            $categories = $this->paramRepo->getParamtersList(self::PARAM_CLE);
            $categoryTitle = $this->paramRepo->findOneBy(['cle' => self::PARAM_CLE, 'ord' => 0]);
            $categoriesFE = new CalCategoriesFE($categoryTitle, $categories);
            $form = $this->createForm(CalCategoriesType::class, $categoriesFE);
            $ = $categoriesFE->getNames();

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $categoryTitle->setDescription($categoriesFE->getDescription());

                /** @var CalCategoryFE $item */
                foreach ($categoriesFE->getValues() as $idx => $item) {
                    $dbId = $categoriesFE->isValueName($item);
                    if (!$dbId) {
                        $categoryItem = new ParamsCalNature($this->entityManager);
                        $categoryItem->setCle(self::PARAM_CLE);
                        $categoryItem->setOrd($categoriesFE->getMaxOrd() + 1);
                        unset($categoriesNames[$item->getDbID()]);
                    } else {
                        $categoryItem = $this->paramRepo->find($dbId);
                        $categoryItem->setUpdatedAt(new DateTimeImmutable('now'));
                    }
                    $categoryItem->setName($item->getName());
                    $categoryItem->setDescription($item->getDescription());
                    $categoryItem->setBackgroundColor($item->getBackgroundColor());
                    $categoryItem->setBorderColor($item->getBorderColor());
                    $categoryItem->setTextColor($item->getTextColor());
                    if (!$categoryItem->getId()) {
                        $this->entityManager->persist($categoryItem);
                        $categoriesFE->setMaxOrd($categoryItem->getOrd());
                    }
                }
                if ($categoriesNames) {
                    reorgList = true;
                    /** il reste des cat evt type non reconduit => suppression */
                    foreach ($categoriesNames as $dbID => $dbItem) {
                        $item = $this->paramRepo->find($dbId);
                        $this->entityManager->remove($item);
                    }
                }
                $this->entityManager->flush();
                if ($reorgList) $this->paramRepo->reorgValues(self::PARAM_CLE);
                $this->addFlash('success', "Table des type d'évèment de calendrier bien enregitrée en base");
                $this->redirectToRoute('bolt_dashboard');
            }
            
            $context['form'] = $form->createView();
        } else {
            $this->addFlash('danger', "La table Paramters n'existe pas, veuillez en avertir l'administrateur");
        }

        return $this->render('@contact-rdv/events/type_gest.html.twig', $context);
    }
}
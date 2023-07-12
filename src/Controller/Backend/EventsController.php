<?php

namespace Celtic34fr\ContactRendezVous\Controller\Backend;

use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Celtic34fr\ContactCore\Entity\Parameter;
use Celtic34fr\ContactCore\Traits\Utilities;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Celtic34fr\ContactRendezVous\Entity\RendezVous;
use Celtic34fr\ContactRendezVous\Form\CalCategoriesType;
use Celtic34fr\ContactCore\Repository\ParameterRepository;
use Celtic34fr\ContactRendezVous\FormEntity\CalCategoryFE;
use Celtic34fr\ContactRendezVous\FormEntity\CalCategoriesFE;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Celtic34fr\ContactRendezVous\EntityRedefine\ParameterCalEvent;
use Celtic34fr\ContactRendezVous\EntityRedefine\ParameterCalEvntType;
use Celtic34fr\ContactRendezVous\Repository\CalEventRepository;

#[Route('events', name: 'evt-')]
class EventsController extends AbstractController
{
    use Utilities;

    const PARAM_CLE = "calNature";
    
    private EntityManagerInterface $entityManager;
    protected $container;
    private $schemaManager;
    private ParameterRepository $parameterRepo;
    private CalEventRepository $calEventRepo;

    public function __construct(EntityManagerInterface $entityManager, ContainerInterface $container,
        ParameterRepository $parameterRepo, CalEventRepository $calEventRepo)
    {
        $this->entityManager = $entityManager;
        $this->schemaManager = $entityManager->getConnection()->getSchemaManager();
        $this->container = $container;
        $this->parameterRepo = $parameterRepo;
        $this->calEventRepo = $calEventRepo;
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
            $categories = $this->parameterRepo->getValuesParamterList(self::PARAM_CLE);
            $categoryTitle = $this->parameterRepo->findOneBy(['cle' => self::PARAM_CLE, 'ord' => 0]);
            $categoriesFE = $this->initCategoriesFe($categoryTitle, $categories);

            $form = $this->createForm(CalCategoriesType::class, $categoriesFE);
            $categoriesNames = $categoriesFE->getNames();

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                if (!$categoryTitle) {
                    $categoryTitle = new Parameter();
                    $categoryTitle->setCle((self::PARAM_CLE));
                    $categoryTitle->setOrd(0);
                }
                $categoryTitle->setValeur($categoriesFE->getDescription());
                if (!$categoryTitle->getId()) $this->entityManager->persist($categoryTitle);

                /** @var CalCategoryFE $item */
                foreach ($categoriesFE->getValues() as $idx => $item) {
                    $dbId = $categoriesFE->isValueName($item);
                    $categoryItem = new ParameterCalEvent();
                    if (!$dbId) {
                        $categoryItem->setOrd($categoriesFE->getMaxOrd() + 1);
                    } else {
                        $categoryItem->setParameter($this->parameterRepo->find($dbId));
                        $categoryItem->setUpdatedAt(new DateTimeImmutable('now'));
                        unset($categoriesNames[$item->getName()]);
                    }
                    $categoryItem->setName($item->getName());
                    $categoryItem->setDescription($item->getDescription());
                    $categoryItem->setBackgroundColor($item->getBackgroundColor());
                    $categoryItem->setBorderColor($item->getBorderColor());
                    $categoryItem->setTextColor($item->getTextColor());
                    if (!$categoryItem->getId()) {
                        $this->entityManager->persist($categoryItem->getParameter());
                        $categoriesFE->setMaxOrd($categoryItem->getOrd());
                    }
                }
                if ($categoriesNames) {
                    $reorgList = true;
                    /** il reste des cat evt type non reconduit => suppression */
                    $this->removeCalevtType($categoriesNames);
                }
                $this->entityManager->flush();
                if ($reorgList) $this->parameterRepo->reorgValues(self::PARAM_CLE);
                $this->addFlash('success', "Table des type d'évèment de calendrier bien enregitrée en base");
                $this->redirectToRoute('bolt_dashboard');
            }
            
            $context['form'] = $form->createView();
        } else {
            $this->addFlash('danger', "La table {$dbPrefix}parameters n'existe pas, veuillez en avertir l'administrateur");
        }

        return $this->render('@contact-rdv/events/type_gest.html.twig', $context);
    }

    private function initCategoriesFE(Parameter $calTitle, array $categories)
    {
        $categoriesFE = new CalCategoriesFE();
        $categoriesFE->setDescription($calTitle->getValeur());
        $categoriesFE->setMaxOrd(sizeof($categories));
        /** @var Parameter category */
        foreach ($categories as $category) {
            $categoryFE = new CalCategoryFE();
            $categoryFE->hydrate($category);
            $categoriesFE->addValue(new CalCategoryFE($category));
        }
        return $categoriesFE;
    }

    private function removeCalEvtType(array $names = []): void
    {
        foreach ($names as $idx => $DBitem) {
            $item = $this->parameterRepo->find($idx);
            if (!$this->calEventRepo->findEventsByCategory($item)) {
                $this->entityManager->remove($item);
            } else {
                $this->addFlash('warning', "Type EvtCal {$DBitem->getName()} impossible à supprimer, encore utilisé");
            }
        }
    }
}
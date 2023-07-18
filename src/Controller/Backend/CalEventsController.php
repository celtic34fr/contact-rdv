<?php

namespace Celtic34fr\ContactRendezVous\Controller\Backend;

use Celtic34fr\ContactCore\Entity\Parameter;
use Celtic34fr\ContactCore\Traits\Utilities;
use Celtic34fr\ContactCore\Repository\ParameterRepository;
use Celtic34fr\ContactRendezVous\Form\CalEventItemsType;
use Celtic34fr\ContactRendezVous\FormEntity\CalEventItem;
use Celtic34fr\ContactRendezVous\FormEntity\CalEventItems;
use Celtic34fr\ContactRendezVous\Repository\CalEventRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

#[Route('events_', name: 'evt-')]
class CalEventsController extends AbstractController
{
    use Utilities;

    const PARAM_CLE = "SysCalNature";
    const PARAM_VALEUR = "Liste des types d'événements de calendrier";

    private $schemaManager;

    public function __construct(private EntityManagerInterface $em, private CalEventRepository $calEventRepo,
        private ParameterRepository $parameterRepo)
    {
        $this->schemaManager = $em->getConnection()->getSchemaManager();
    }

    #[Route('list', name: 'list')]
    public function eventList()
    {
        $dbPrefix = $this->getParameter('bolt.table_prefix');

        /** contrôle existance table nécessaire à la méthode */
        if ($this->existsTable($dbPrefix . 'cal_events') == true) {
        } else {
            $this->addFlash('danger', "La table {$dbPrefix}cal_events n'existe pas, veuillez en avertir l'administrateur");
        }
    }

    #[Route('input', name: 'input')]
    public function eventInput()
    {
        $dbPrefix = $this->getParameter('bolt.table_prefix');

        /** contrôle existance table nécessaire à la méthode */
        if ($this->existsTable($dbPrefix . 'cal_events') == true) {
        } else {
            $this->addFlash('danger', "La table {$dbPrefix}cal_events n'existe pas, veuillez en avertir l'administrateur");
        }
    }

    #[Route('type_gest', name: 'type-gest')]
    public function eventTypeGest()
    {
        $dbPrefix = $this->getParameter('bolt.table_prefix');
        $twig_context = [];

        /** contrôle existance table nécessaire à la méthode */
        if ($this->existsTable($dbPrefix . 'parameters') == true) {
            /** rrecherche des informations de base */
            $calEventEntete = $this->parameterRepo->findOneBy(['cle' => self::PARAM_CLE, 'ord' => 0]);
            $calEventItems = $this->parameterRepo->findItemsByCle(self::PARAM_CLE);

            if (!$calEventEntete) {
                /** par encore de liste de paramètre SysCalEvent = création */
                $calEventEntete = new Parameter();
                $calEventEntete->setCle(self::PARAM_CLE)->setOrd(0)->setValeur(self::PARAM_VALEUR);
                $this->em->persist($calEventEntete);
                $this->em->flush();
            }

            /** mise en place du formulaire à partir de $calEventItems */
            $items = new CalEventItems();
            if ($calEventItems) {
                foreach ($calEventItems as $calEventItem) {
                    $item = new CalEventItem();
                    $item->hydrateFromJson($calEventItem->getValeur());
                    $items->addItem($item);
                }
            }
            $form = $this->createForm(CalEventItemsType::class, $items);

            $twig_context['entete']= $calEventEntete;
            $twig_context['form'] = $form->createView();
        } else {
            $this->addFlash('danger', "La table {$dbPrefix}parameters n'existe pas, veuillez en avertir l'administrateur");
            $twig_context['entete'] = null;
        }

        return $this->render("@contact-rdv/cal_events/type_gest.html.twig", $twig_context);
    }
}

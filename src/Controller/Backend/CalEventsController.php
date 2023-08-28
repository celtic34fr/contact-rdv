<?php

namespace Celtic34fr\ContactRendezVous\Controller\Backend;

use Celtic34fr\ContactCore\Entity\Parameter;
use Celtic34fr\ContactCore\Repository\ParameterRepository;
use Celtic34fr\ContactCore\Traits\FormErrorsTrait;
use Celtic34fr\ContactCore\Traits\UtilitiesTrait;
use Celtic34fr\ContactRendezVous\Form\CalEventItemsType;
use Celtic34fr\ContactRendezVous\Form\InputEventType;
use Celtic34fr\ContactRendezVous\FormEntity\CalEventItem;
use Celtic34fr\ContactRendezVous\FormEntity\CalEventItems;
use Celtic34fr\ContactRendezVous\FormEntity\InputEvent;
use Celtic34fr\ContactRendezVous\Repository\CalEventRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('events_', name: 'evt-')]
class CalEventsController extends AbstractController
{
    use UtilitiesTrait;
    use FormErrorsTrait;

    const PARAM_CLE = "SysCalNature"; // nom de la liste de valeur dans Parameters
    const PARAM_VALEUR = "Liste des types d'événements de calendrier";

    private $schemaManager;

    public function __construct(
        private EntityManagerInterface $em,
        private CalEventRepository $calEventRepo,
        private ParameterRepository $parameterRepo
    ) {
        $this->schemaManager = $em->getConnection()->getSchemaManager();
    }

    #[Route('list', name: 'list')]
    /** eventLisy : Affichage de la liste des événements, rendez-vous à venir */
    public function eventList(Request $request)
    {
        $dbPrefix = $this->getParameter('bolt.table_prefix');
        $events = [];
        $page = $request->query->get("ELPage") ?? 1;
        $limit = $page ? 10 : 0;

        /* contrôle existance table nécessaire à la méthode */
        if ($this->existsTable($dbPrefix . 'cal_events') == true) {
            $events = $this->calEventRepo->findAllPaginateFromDate($page, $limit);
        } else {
            $this->addFlash('danger', "La table {$dbPrefix}cal_events n'existe pas, veuillez en avertir l'administrateur");
        }

        return $this->render("@contact-rdv/cal_events/events_list.html.twig", [
            'events' => $events,
        ]);
    }

    #[Route('input', name: 'input')]
    /** Sasie d'un événement, un rendez-vous dans le calendrier */
    public function eventInput(Request $request)
    {
        $dbPrefix = $this->getParameter('bolt.table_prefix');
        $twig_context = [];

        /** contrôle existance table nécessaire à la méthode 'cal_events' */
        if ($this->existsTable($dbPrefix . 'cal_events') == true) {
            $now = new DateTime('now');
            $date_min = $now->format("d/m/Y");
            $hour_min = $now->format("h:m");
            $allEvents = $this->calEventRepo->findAllPaginateFromDate(1);
            if ($allEvents) $twig_context['allEvents'] = $allEvents;

            $inputEvent = new InputEvent();
            $form = $this->createForm(InputEventType::class, $inputEvent);

            $form->handleRequest($request);

            if ($form->isSubmitted()) {
                if ($form->isValid()) {
                    dump('inputEvent form soumis et balide');
                }
            }

            $twig_context['date_min'] = $date_min;
            $twig_context['hour_min'] = $hour_min;
            $twig_context['form'] = $form->createView();
        } else {
            $this->addFlash('danger', "La table {$dbPrefix}cal_events n'existe pas, veuillez en avertir l'administrateur");
        }

        return $this->render("@contact-rdv/cal_events/input.html.twig", $twig_context);
    }

    #[Route('type_gest', name: 'type-gest')]
    /** Gestion des types d'événements, rendez-vous : ajout - modification - suppression */
    public function eventTypeGest(Request $request)
    {
        /** récupération du préfix de création des table dans Bolt CMS */
        $dbPrefix = $this->getParameter('bolt.table_prefix');
        $twig_context = [];
        $dbEvtKeys = [];

        /** contrôle existance table nécessaire à la méthode 'parameters' */
        if ($this->existsTable($dbPrefix . 'parameters') == true) {
            /** recherche des informations de base */
            $calEventEntete = $this->parameterRepo->findOneBy(['cle' => self::PARAM_CLE, 'ord' => 0]);
            $calEventItems = $this->parameterRepo->findItemsByCle(self::PARAM_CLE);
            $errors = [];

            if (!$calEventEntete) {
                /** pas encore de liste de paramètres SysCalEvent => création entête */
                $calEventEntete = new Parameter();
                $calEventEntete->setCle(self::PARAM_CLE)->setOrd(0)->setValeur(self::PARAM_VALEUR);
                $this->em->persist($calEventEntete);
                $this->em->flush();
            }

            /** mise en place du formulaire à partir de $calEventItems trouvés en base */
            $items = new CalEventItems();
            if ($calEventItems) {
                foreach ($calEventItems as $calEventItem) {
                    $item = new CalEventItem();
                    $item->hydrateFromJson($calEventItem->getValeur());
                    $item->setId($calEventItem->getId());
                    $items->addItem($item);
                    $dbEvtKeys[$item->getCle()] = $item->getId();
                }
            }
            $form = $this->createForm(CalEventItemsType::class, $items);

            $form->handleRequest($request);

            if ($form->isSubmitted()) {
                if ($form->isValid()) {
                    /** traitement du formulaire soumis et validé par Symfony */
                    $idx = 0;
                    $formItems = $this->getFormItems($_POST);
                    /** @var CalEventItem $item */
                    foreach ($formItems->getItems() as $item) {
                        $idx++;
                        /* recherche de l'item de la liste de paramètres pour modification */
                        $calEvtItem = $this->parameterRepo->findByPartialFields(['valeur' => $item->getCle()]);
                        if (!$calEvtItem) {
                            $calEvtItem = new Parameter();
                            $calEvtItem->setCle(self::PARAM_CLE);
                        } else {
                            if (sizeof($calEvtItem) > 1) {
                                throw new Exception("Evt Type {$item->getCle()} existe plusieurs fois : incohérent");
                            }
                            $calEvtItem = $calEvtItem[0];
                            unset($dbEvtKeys[$item->getCle()]);
                        }
                        $calEvtItem->setOrd($idx);
                        $calEvtItem->setValeur($item->getValaur());
                        if (!$item->getId()) {
                            $this->em->persist($calEvtItem);
                        }
                    }
                    $this->em->flush();

                    /** traitement des clé non reconduites */
                    if ($dbEvtKeys) {
                        foreach ($dbEvtKeys as $dbEvtKey => $dbId) {
                            $item = $this->parameterRepo->find($dbId);
                            if ($this->calEventRepo->findEventsByCategory($item)) {
                                /** duppression impossible => existe des événement avec cet type */
                                $this->addFlash('warning', "Le type d'évèment $dbEvtKey est utilisé, suppression impossible");
                                $idx++;
                                $item->setOrd($idx);
                            } else {
                                $this->em->remove($item);
                            }
                        }
                        $this->em->flush();
                    }

                    $this->addFlash('success', "Table des types d'évèments de calendrier a été bien enregitrée en base");
                    return $this->redirectToRoute('bolt_dashboard', [], 303);
                } else {
                    /** recherche des erreurs dans les sous formulaires */
                    $errors = $this->formatErrors($this->getErrors($form));
                }
            }

            $twig_context['entete'] = $calEventEntete;
            $twig_context['form'] = $form->createView();
            $twig_context['errors'] = $errors;
        } else {
            $this->addFlash('danger', "La table {$dbPrefix}parameters n'existe pas, veuillez en avertir l'administrateur");
            $twig_context['entete'] = null;
            $twig_context['form'] = null;
            $twig_context['errors'] = null;
        }

        return $this->render("@contact-rdv/cal_events/type_gest.html.twig", $twig_context);
    }

    private function formatErrors(array $rawErrors): array
    {
        $formatedErrors = [];
        $rawErrors = $rawErrors['Liste des catégories'];
        foreach ($rawErrors as $occurs => $errorsOccurs) {
            foreach ($errorsOccurs as $field => $errors) {
                $formatedFieldErrors = "";
                foreach ($errors as $error) {
                    $formatedFieldErrors .= "<p>" . $error . "</p>";
                }
                if (!array_key_exists($occurs, $formatedErrors)) $formatedErrors[$occurs] = [];
                $formatedErrors[$occurs][$field] = $formatedFieldErrors;
            }
        }
        return $formatedErrors;
    }

    private function getFormItems(array $post): CalEventItem
    {
        $formItems = new CalEventItem();

        dd($post);

        $post = $post['cal_event_type'] ?? [];
        $post = $post['items'] ?? [];
        $item = new CalEventItem();
        $item->hydratefromArray($post);
        $items->addItem($item);

        return $formItems;
    }
}

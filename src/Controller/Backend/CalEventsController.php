<?php

namespace Celtic34fr\ContactRendezVous\Controller\Backend;

use Doctrine\ORM\EntityManagerInterface;
use Celtic34fr\ContactCore\Entity\Parameter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Celtic34fr\ContactCore\Traits\UtilitiesTrait;
use Celtic34fr\ContactCore\Traits\FormErrorsTrait;
use Celtic34fr\ContactRendezVous\Form\CalEventItemsType;
use Celtic34fr\ContactRendezVous\FormEntity\CalEventItem;
use Celtic34fr\ContactCore\Repository\ParameterRepository;
use Celtic34fr\ContactRendezVous\FormEntity\CalEventItems;
use Celtic34fr\ContactRendezVous\Repository\CalEventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('events_', name: 'evt-')]
class CalEventsController extends AbstractController
{
    use UtilitiesTrait;
    use FormErrorsTrait;

    const PARAM_CLE = "SysCalNature";
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
    public function eventTypeGest(Request $request)
    {
        $dbPrefix = $this->getParameter('bolt.table_prefix');
        $twig_context = [];

        /** contrôle existance table nécessaire à la méthode */
        if ($this->existsTable($dbPrefix . 'parameters') == true) {
            /** rrecherche des informations de base */
            $calEventEntete = $this->parameterRepo->findOneBy(['cle' => self::PARAM_CLE, 'ord' => 0]);
            $calEventItems = $this->parameterRepo->findItemsByCle(self::PARAM_CLE);
            $errors = [];

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
                    $items->addItemCalEvent($item);
                }
            }
            $form = $this->createForm(CalEventItemsType::class, $items);

            $form->handleRequest($request);

            if ($form->isSubmitted()) {
                if ($form->isValid()) {
                    /** @var CalEventItem $item */
                    foreach ($items as $idx => $item) {
                        if ($item->getId()) {
                            $calEvtItem = $this->parameterRepo->find($item->getId());
                        } else {
                            $calEvtItem = new Parameter();
                            $calEvtItem->setCle(self::PARAM_CLE);
                        }
                        $calEvtItem->setOrd($idx + 1);
                        $calEvtItem->setValeur($item->getValaur());
                        if (!$item->getId()) {
                            $this->em->persist($calEvtItem);
                        }
                    }
                    $this->em->flush();
                    $this->addFlash('success', "Table des type d'évèment de calendrier bien enregitrée en base");
                    $this->redirectToRoute('bolt_dashboard');
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
                $formatedFieldErrors ="";
                foreach ($errors as $error) {
                    $formatedFieldErrors .= "<p>".$error."</p>";
                }
                if (!array_key_exists($occurs, $formatedErrors)) $formatedErrors[$occurs] = [];
                $formatedErrors[$occurs][$field] = $formatedFieldErrors;
            }
        }
        return $formatedErrors;
    }
}

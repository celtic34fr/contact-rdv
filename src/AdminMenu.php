<?php

namespace Celtic34fr\ContactRendezVous;

use Knp\Menu\MenuItem;
use Bolt\Menu\ExtensionBackendMenuInterface;
use Celtic34fr\ContactCore\Traits\AdminMenuTrait;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class AdminMenu implements ExtensionBackendMenuInterface
{
    public function __construct(private UrlGeneratorInterface $urlGenerator)
    {
    }

    use AdminMenuTrait;

    public function addItems(MenuItem $menu): void
    {
        if (!$menu->getChild("Gestion des Contacts")) {
            $menu->addChild('Gestion des Contacts', [
                'extras' => [
                    'name' => 'Gestion des Contacts',
                    'type' => 'separator',
                    'group' => 'CRM',
                ]
            ]);
        }

        /* 1/ décomposition de $menu en $menuBefor, $menuContacts et $menu after */
        list($menuBefore, $menuContacts, $menuAfter) = $this->extractsMenus($menu);

        $rendezVous = [
            'Les Rendez-Vous' => [
                'type' => 'menu',
                'item' => [
                    'uri' => $this->urlGenerator->generate('bolt_menupage', [
                        'slug' => 'rendez_vous',
                    ]),
                    'extras' => [
                        'group' => 'CRM',
                        'name' => 'Les Rendez-Vous',
                        'slug' => 'rendez_vous',
                        'icon' => 'fa-calendar',
                    ]
                ]
            ],
            'Futurs rendez-vous, évènements'  => [
                'type' => 'smenu',
                'parent' => 'Les Rendez-Vous',
                'item' => [
                    'uri' => $this->urlGenerator->generate('evt-list'),
                    'extras' => [
                        'icon' => 'fa-calendar-alt',
                        'group' => 'CRM',
                    ]
                ]
            ],
            'Saisir un rendez-vous, une évènement' => [
                'type' => 'smenu',
                'parent' => 'Les Rendez-Vous',
                'item' => [
                    'uri' => $this->urlGenerator->generate('evt-input'),
                    'extras' => [
                        'icon' => 'fa-user-clock',
                        'group' => 'CRM',
                    ]
                ]
            ],
            "Gestion des types d'évènements" => [
                'type' => 'smenu',
                'parent' => 'Les Rendez-Vous',
                'item' => [
                    'uri' => $this->urlGenerator->generate('evt-type-gest'),
                    'extras' => [
                        'icon' => 'fa-tool',
                        'group' => 'CRM',
                    ]
                ]
            ]
        ];
        $menuContacts = $this->addMenu($rendezVous, $menuContacts);

        if ($menuContacts->hasChild("Utilitaires")) {
            /** extraction menu 'Utilitaires' et mise en fin du bloc menu */
            $utilitaires = $menuContacts['Utilitaires'];
            unset($menuContacts['Utilitaires']);
            $menuContacts->addChild($utilitaires);
        }

        /* 4/ recontruction de $menu avec $menuBefore, $menuContacts et $menuAfter */
        $menu = $this->rebuildMenu($menu, $menuBefore, $menuContacts, $menuAfter);
    }
}
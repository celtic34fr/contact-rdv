<?php

namespace Celtic34fr\ContactRendezVous;

use Knp\Menu\MenuItem;
use Bolt\Menu\ExtensionBackendMenuInterface;
use Celtic34fr\ContactCore\Trait\AdminMenuTrait;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class AdminMenu implements ExtensionBackendMenuInterface
{

    public function __construct(private UrlGeneratorInterface $urlGenerator)
    {
    }

    use AdminMenuTrait;

    public function addItems(MenuItem $menu): void
    {
        /* 1/ décomposition de $menu en $menuBefor, $menuContacts et $menu after */
        list($menuBefore, $menuContacts, $menuAfter) = $this->extractsMenus($menu);

        /* 2/ ajout des menu de gestion du module Rendez-Vous */
        $rendezVous = [
            'Gestion des Rendez-Vous', [
                'type' => 'menu',
                'item' => [
                    'uri' => $this->urlGenerator->generate('bolt_menupage', [
                        'slug' => 'rendez_vous',
                    ]),
                    'extras' => [
                        'group' => 'CRM',
                        'name' => 'Gestion des Rendez-Vous',
                        'slug' => 'rendez_vous',
                    ]
                ]
            ],
            'Futurs rendez-vous, évènements', [
                'type' => 'smenu',
                'parent' => 'Gestion des Rendez-Vous',
                'item' => [
                    'uri' => $this->urlGenerator->generate('next_events'),
                    'extras' => [
                        'icon' => 'fa-calendar',
                        'group' => 'CRM',
                    ]
                ]
            ],
            'Saisir un rendez-vous, une évènement', [
                'type' => 'smenu',
                'parent' => 'Gestion des Rendez-Vous',
                'item' => [
                    'uri' => $this->urlGenerator->generate('evt_input'),
                    'extras' => [
                        'icon' => 'fa-calendar-lines-pen',
                        'group' => 'CRM',
                    ]
                ]
            ]
        ];
        $menuContacts = $this->addMenu($rendezVous, $menuContacts);


        /* 4/ recontruction de $menu avec $menuBefore, $menuContacts et $menuAfter */
        $menu = $this->rebuildMenu($menu, $menuBefore, $menuContacts, $menuAfter);
    }
}

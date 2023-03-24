<?php

namespace Celtic34fr\ContactRendezVous;

use Bolt\Menu\ExtensionBackendMenuInterface;
use Knp\Menu\MenuItem;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class AdminMenu implements ExtensionBackendMenuInterface
{
    public function __construct(UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

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

        $children = $menu->getChildren();
        $childrenUpdated = [];
        $crm = false;
        $saveName = "";
        $idx = 0;

        foreach ($children as $name => $child) {
            if ((!$child->getExtra('group') || $child->getExtra('group') != 'CRM') && !$crm) {
                $childrenUpdated[$name] = $child;
                $idx += 1;
            } elseif (!$crm) {
                $crm = true;
                $childrenUpdated[$name] = $child;
                $idx += 1;
            } else {
                $saveName = $name;
                break;
            }
        }
        $menu->setChildren($childrenUpdated);

        $menu->addChild('Gestion des Rendez-Vous', [
            'uri' => $this->urlGenerator->generate('bolt_menupage', [
                'slug' => 'rendez_vous',
            ]),
            'extras' => [
                'group' => 'CRM',
                'name' => 'Gestion des Rendez-Vous',
                'slug' => 'rendez_vous',
            ]
        ]);

        $menu['Gestion des Rendez-Vous']->addChild('Futurs rendez-vous, évènements', [
            'uri' => $this->urlGenerator->generate('evt_list'),
            'extras' => [
                'icon' => 'fa-clipboard-question',
                'group' => 'CRM',
            ]
        ]);
        $menu['Gestion des Rendez-Vous']->addChild('Saisir un rendez-vous, une évènement', [
            'uri' => $this->urlGenerator->generate('evt_input'),
            'extras' => [
                'icon' => 'fa-clipboard-question',
                'group' => 'CRM',
            ]
        ]);

        if ($saveName) {
            $childrenUpdated = $menu->getChildren();
            $find = false;
            foreach ($children as $name => $child) {
                if ($name === $saveName || $find) {
                    $childrenUpdated[$name] = $child;
                    $find = true;
                }
            }
            $menu->setChildren($childrenUpdated);
        }
    }
}
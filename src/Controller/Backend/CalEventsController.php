<?php

namespace Celtic34fr\ContactRendezVous\Controller\Backend;

use Celtic34fr\ContactCore\Traits\Utilities;
use Symfony\Component\Routing\Annotation\Route;

#[Route('events', name: 'evt-')]
class CalEventsController
{
    use Utilities;

    const PARAM_CLE = "SysCalNature";

    #[Route('list', name: 'list')]
    public function eventList()
    {
    }

    #[Route('input', name: 'input')]
    public function eventInput()
    {
    }

    #[Route('type_gest', name: 'type-gest')]
    public function eventTypeGest()
    {
    }
}

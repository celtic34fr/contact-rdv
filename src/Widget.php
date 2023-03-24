<?php

namespace Celtic34fr\ContactRendezVous;

use Bolt\Widget\BaseWidget;
use Bolt\Widget\CacheAwareInterface;
use Bolt\Widget\CacheTrait;
use Bolt\Widget\Injector\AdditionalTarget;
use Bolt\Widget\Injector\RequestZone;
use Bolt\Widget\StopwatchAwareInterface;
use Bolt\Widget\StopwatchTrait;
use Bolt\Widget\TwigAwareInterface;

class Widget extends BaseWidget implements TwigAwareInterface, CacheAwareInterface, StopwatchAwareInterface
{
    use CacheTrait;
    use StopwatchTrait;

    public function __construct()
    {
        $this->name = 'Contact RendezVous Widget';
        $this->target = ADDITIONALTARGET::WIDGET_BACK_DASHBOARD_ASIDE_TOP;
        $this->priority = 300;
        $this->template = '@contact-rdv/widget/rendezvous.html.twig';
        $this->zone = RequestZone::FRONTEND;
        $this->cacheDuration = 0;
    }

    public function run(array $params = []): ?string
    {
        return parent::run([]);
    }
}
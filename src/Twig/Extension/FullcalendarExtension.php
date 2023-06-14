<?php

namespace Celtic34fr\ContactRendezVous\Twig\Extension;

use Twig\TwigFunction;
use Twig\Extension\AbstractExtension;
use Celtic34fr\ContactRendezVous\Twig\Runtime\FullcalendarRuntime;

/**
 * TWIG Extension : helpers for parameter up a Fullcalendar object functions definitions
 */
class FullcalendarExtension extends AbstractExtension
{
    const SAFE = ['is_safe' => ['html']];

    /**
     * Extension function definitions
     * 
     * @return array<TwigFunction>
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('getStartDate', [FullcalendarRuntime::class, 'twigFunction_getStartDate']),
            new TwigFunction('getEndDate', [FullcalendarRuntime::class, 'twigFunction_getEndDate']),
        ];
    }
}

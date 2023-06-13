<?php

namespace Celtic34fr\ContactRendezVous\Twig\Extension;

use Twig\TwigFunction;
use Twig\Extension\AbstractExtension;
use Celtic34fr\ContactRendezVous\Twig\Runtime\FullcalendarRuntime;

class FullcalendarExtension extends AbstractExtension
{
    const SAFE = ['is_safe' => ['html']];

    public function getFunctions(): array
    {
        return [
            new TwigFunction('getStartDate', [FullcalendarRuntime::class, 'twigFunction_getStartDate']),
            new TwigFunction('getEndDate', [FullcalendarRuntime::class, 'twigFunction_getEndDate']),
        ];
    }
}

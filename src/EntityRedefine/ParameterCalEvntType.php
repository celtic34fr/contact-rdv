<?php

namespace Celtic34fr\ContactRendezVous\EntityRedefine;
use Celtic34fr\ContactCore\Repository\ParameterRepository;
use DateTimeImmutable;
use Celtic34fr\ContactCore\Entity\Parameter;
use Celtic34fr\ContactCore\Traits\ParametersEntityTrait;

class ParameterCalEvntType
{
    const HEADER = [
        'name', 'description', 'backgroundColor', 'borderColor', 'textColor'
    ];
    const PARAM_CLE = "calNature";

    private Parameter $param;
    private ParameterRepository $repository;

    public function __construct(ParameterRepository $repository)
    {
        $this->param = new Parameter();
        $this->param->setCle(self::PARAM_CLE);
        $this->repository = $repository;
    }

    use ParametersEntityTrait;
    public function getName(): mixed
    {
        return $this->getItem('name');
    }

    public function setName(string $name): mixed
    {
        return $this->setItem('name', $name);
    }

    public function getDescription(): mixed
    {
        return $this->getItem('description');
    }

    public function setDescription(string $name): mixed
    {
        return $this->setItem('description', $name);
    }

    public function getBackgroundColor(): string
    {
        return $this->getItem('backgroundColor');
    }

    public function setBackgroundColor(string $backgroundColor)
    {
        return $this->setItem('backgroundColor', $backgroundColor);
    }

    public function getBorderColor(): string
    {
        return $this->getItem('borderColor');
    }

    public function setBorderColor(string $borderColor)
    {
        return $this->setItem('borderColor', $borderColor);
    }

    public function getTextColor(): string
    {
        return $this->getItem('textColor');
    }

    public function setTextColor(string $textColor)
    {
        return $this->setItem('textColor', $textColor);
    }
}

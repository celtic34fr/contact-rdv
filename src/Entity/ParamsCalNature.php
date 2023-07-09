<?php

namespace Celtic34fr\ContactRendezVous\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Celtic34fr\ContactCore\Entity\Parameter;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Celtic34fr\ContactCore\Traits\ParametersEntityTrait;
use Celtic34fr\ContactCore\Repository\ParameterRepository;

class ParamsCalNature extends Parameter
{
    const HEADER = [
        'name', 'description', 'backgroudColor', 'borderColor', 'textColor'
    ];
    const PARAM_CLE = "calNature";

    private EntityRepository $repository;

    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct();
        $this->repository = $em->getRepository(Parameter::class);
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

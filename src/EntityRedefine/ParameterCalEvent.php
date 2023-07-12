<?php

namespace Celtic34fr\ContactRendezVous\EntityRedefine;

use Celtic34fr\ContactCore\Entity\Parameter;

class ParameterCalEvent
{
    const HEADER = [
        'name', 'description', 'backgroundColor', 'borderColor', 'textColor'
    ];

    private array $values;

    public function __construct(private Parameter $parameter)
    {
        $this->values = $this->array_combine(self::HEADER, $this->getValues());
    }

    public function getParameter(): Parameter
    {
        return $this->parameter;
    }

    /**
     * Set the value of parameter
     * @return  self
     */
    public function setParameter(Parameter $parameter)
    {
        $this->parameter = $parameter;
        return $this;
    }

    public function getId(): ?int
    {
        return $this->parameter->getId();
    }

    public function getOrd(): mixed
    {
        return $this->parameter->getOrd() ?? false;
    }

    public function setOrd(int $ord): self
    {
        $this->parameter->setOrd($ord);
        return $this;
    }

    public function getValues(): array
    {
        $datas = $this->parameter->getValeur() ?? [];
        if ($datas) {
            $datas = explode("|", $datas);
            return $this->array_combine(self::HEADER, $datas);
        }
        return [];
    }

    public function setValues(array $values): self
    {
        $chaine = implode('|', $values);
        $this->parameter = $this->parameter->setValeur($chaine);
        return $this;
    }

    public function getItem(string $key): mixed
    {
        if (!array_key_exists($key, self::HEADER)) return false;
        return $this->getValues()[$key];
    }

    public function setItem(string $key, string $val): mixed
    {
        if (!array_key_exists($key, self::HEADER)) return false;
        $datas = $this->getValues();
        $datas[$key] = $val;
        return $this->setValues($datas);
    }

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

    private function array_combine(array $keys, array $values): array
    {
        $arrayComnined = [];
        $maxIdx = max(sizeof($keys), sizeof($values));
        for ($idx = 0; $idx < $maxIdx; $idx++) {
            $lkey = array_key_exists($idx, $keys) ? $keys[$idx] : $idx;
            $lvalue = array_key_exists($idx, $values) ? $values[$idx] : null;
            $arrayComnined[$lkey] = $lvalue;
        }
        return $arrayComnined;
    }
}

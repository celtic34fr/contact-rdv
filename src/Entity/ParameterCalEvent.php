<?php

namespace Celtic34fr\ContactRendezVous\Entity;

use Celtic34fr\ContactCore\Entity\Parameter;

class ParameterCalEvent extends Parameter
{
    /**
    const HEADER = [
        'name', 'description', 'backgroundColor', 'borderColor', 'textColor'
    ];
    const PARAM_CLE = "calNature";

    private array $values = [];

    public function __construct(private ?Parameter $parameter = null)
    {
        if ($parameter) {
            $this->values = $this->array_combine(self::HEADER, $this->getValues());
        } else {
            $this->parameter = new Parameter();
            $this->parameter->setCle(self::PARAM_CLE);
        }
    }

    public function getParameter(): Parameter
    {
        return $this->parameter;
    }
     */

    /**
     * Set the value of parameter
     * @return  self
     */
    /**
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

    public function getUpdatedAt(): mixed
    {
        return $this->parameter->getUpdatedAt() ?? false;
    }

    public function setUpdatedAt(DateTimeImmutable $updatedAt): self
    {
        $this->parameter->setUpdatedAt($updatedAt);
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
     */

    public function getName(): mixed
    {
        return array_key_exists('name', $this->getValeur()) ? $this->getValeur()['name'] : "";
    }

    public function setName(string $name): mixed
    {
        $datas = $this->getValeur();
        $datas['name'] = $name;
        $this->setValeur($datas);
        return $this;
    }

    public function getDescription(): mixed
    {
        return array_key_exists('description', $this->getValeur()) ? $this->getValeur()['description'] : "";
    }

    public function setDescription(string $description): mixed
    {
        $datas = $this->getValeur();
        $datas['description'] = $description;
        $this->setValeur($datas);
        return $this;
    }

    public function getBackgroundColor(): string
    {
        return array_key_exists('backgroundColor', $this->getValeur()) ? $this->getValeur()['backgroundColor'] : "";
    }

    public function setBackgroundColor(string $backgroundColor)
    {
        $datas = $this->getValeur();
        $datas['backgroundColor'] = $backgroundColor;
        $this->setValeur($datas);
        return $this;
    }

    public function getBorderColor(): string
    {
        return array_key_exists('borderColor', $this->getValeur()) ? $this->getValeur()['borderColor'] : "";
    }

    public function setBorderColor(string $borderColor)
    {
        $datas = $this->getValeur();
        $datas['borderColor'] = $borderColor;
        $this->setValeur($datas);
        return $this;
    }

    public function getTextColor(): string
    {
        return array_key_exists('textColor', $this->getValeur()) ? $this->getValeur()['textColor'] : "";
    }

    public function setTextColor(string $textColor)
    {
        $datas = $this->getValeur();
        $datas['textColor'] = $textColor;
        $this->setValeur($datas);
        return $this;
    }
}

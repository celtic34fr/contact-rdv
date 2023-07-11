<?php

namespace Celtic34fr\ContactRendezVous\FormEntity;
use Celtic34fr\ContactCore\Entity\Parameter;
use Celtic34fr\ContactCore\Repository\ParameterRepository;
use Celtic34fr\ContactRendezVous\EntityRedefine\ParameterCalEvntType;

class CalCategoryFE
{
    private ?int $dbID = null;
    private ?string $name;
    private ?string $description;
    private ?string $backgroundColor;
    private ?string $borderColor;
    private ?string $textColor;

    public function __construct(?Parameter $parameter = null, ParameterRepository $parameterRepo)
    {
        if ($parameter) {
            $item = new ParameterCalEvntType($parameterRepo);
            $item->setParam($parameter);
            $this->hydrateValues($item->getValues());
            $this->dbID = $item->getId();
        }
    }

    public function getDbID(): ?int
    {
        return $this->dbID;
    }

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Set the value of name
     * @return  self
     */ 
    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * Set the value of description
     * @return  self
     */ 
    public function setDescription(string $description): self
    {
        $this->description = $description;
        return $this;
    }

    public function getBackgroundColor(): string
    {
        return $this->backgroundColor;
    }

    /**
     * Set the value of backgroundColor
     * @return  self|bool
     */ 
    public function setBackgroundColor(string $backgroundColor): mixed
    {
        if (false === $this->validColorHexa($backgroundColor)) return false;
        $this->backgroundColor = $backgroundColor;
        return $this;
    }

    public function getBorderColor(): string
    {
        return $this->borderColor;
    }

    /**
     * Set the value of borderColor
     * @return  self|bool
     */ 
    public function setBorderColor(string $borderColor): self
    {
        if (false === $this->validColorHexa($borderColor)) return false;
        $this->borderColor = $borderColor;
        return $this;
    }

    public function getTextColor(): string
    {
        return $this->textColor;
    }

    /**
     * Set the value of textColor
     * @return  self|bool
     */ 
    public function setTextColor(string $textColor): mixed
    {
        if (false === $this->validColorHexa($textColor)) return false;
        $this->textColor = $textColor;
        return $this;
    }

    private function validColorHexa(string $color_str): bool
    {
        /** validation de la chaîne de caractères
         *      -> commencer par '#'
         *      -> par groupe de 2 caractères : valeur hexadéciaml de 0 à 255 : 00 à FF
         */
        if (substr($color_str, 0, 1) != "#") return false;
        if (!ctype_xdigit(substr($color_str, 1))) return false;
        return true;
    }

    private function hydrateValues(array $values): void
    {
        foreach ($values as $key => $value) {
            $this->$key = $value;
        }
    }
}

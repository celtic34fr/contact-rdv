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
    private ?string $background_color;
    private ?string $border_color;
    private ?string $text_color;

    public function __construct(?Parameter $parameter = null, ParameterRepository $parameterRepo)
    {
        if ($parameter) {
            $item = new ParameterCalEvntType($parameterRepo);
            $item->setParam($parameter);
            list($this->name, $this->description, $this->background_color, $this->border_color, $this->text_color) =
            $item->getValues();
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
        return $this->background_color;
    }

    /**
     * Set the value of background_color
     * @return  self|bool
     */ 
    public function setBackgroundColor(string $background_color): mixed
    {
        if (false === $this->validColorHexa($background_color)) return false;
        $this->background_color = $background_color;
        return $this;
    }

    public function getBorderColor(): string
    {
        return $this->border_color;
    }

    /**
     * Set the value of border_color
     * @return  self|bool
     */ 
    public function setBorderColor(string $border_color): self
    {
        if (false === $this->validColorHexa($border_color)) return false;
        $this->border_color = $border_color;
        return $this;
    }

    public function getTextColor(): string
    {
        return $this->text_color;
    }

    /**
     * Set the value of text_color
     * @return  self|bool
     */ 
    public function setTextColor(string $text_color): mixed
    {
        if (false === $this->validColorHexa($text_color)) return false;
        $this->text_color = $text_color;
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
}

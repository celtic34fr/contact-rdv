<?php

namespace Celtic34fr\ContactRendezVous\FormEntity;

use Celtic34fr\ContactCore\Entity\Parameter;
use Celtic34fr\ContactRendezVous\FormEntity\CalCategoryFE;

class CalCategoriesFE
{
    private string $description;
    private array $values;

    public function __construct(?Parameter $paramTitle = null, ?array $paramList = null)
    {
        $this->description = $paramTitle ? $paramTitle->getValeur() :"";
        if ($paramList) {
            foreach ($paramList as $paramItem) {
                $this->values[] = new CalCategoryFE($paramItem);
            }
        }

    }

    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * Set the value of description
     * @return  self
     */ 
    public function setDescription(string $description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * Get the value of values
     * @return  array[CalCategoryFE]
     */ 
    public function getValues(): array
    {
        return $this->values;
    }

    /**
     * Set the value of values
     * @return  self
     */ 
    public function setValues(array $values): self
    {
        $this->values = $values;
        return $this;
    }

    public function addValue(CalCategoryFE $value): mixed
    {
        if (in_array($value, $this->values)) return false;
        
        $this->values[] = $value;
        return $this;
    }

    public function removeValue(CalCategoryFE $value): mixed
    {
        if (in_array($value, $this->values)) {
            $idx = array_search($value, $this->values);
            unset($this->values[$idx]);
            return $this;
        }

        return false;
    }
}

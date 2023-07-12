<?php

namespace Celtic34fr\ContactRendezVous\FormEntity;

use Celtic34fr\ContactRendezVous\FormEntity\CalCategoryFE;

class CalCategoriesFE
{
    private string $description = "";
    private array $values = [];
    private array $names = [];
    private int $maxOrd = 0;

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
        $this->names[$value->getName()] = $value->getId() ?? 0;
        return $this;
    }

    public function removeValue(CalCategoryFE $value): mixed
    {
        if (in_array($value, $this->values)) {
            $idx = array_search($value, $this->values);
            unset($this->values[$idx]);
            unset($this->names[$value->getName()]);
            return $this;
        }

        return false;
    }

    public function isValueName(CalCategoryFE $calCategory): mixed
    {
        if (array_key_exists($calCategory->getName(), $this->names)) {
            return $this->names[$calCategory->getName()];
        }
        return false;
    }

    public function getNames()
    {
        return $this->names;
    }

    public function getMaxOrd(): int
    {
        return $this->maxOrd;
    }

    /**
     * Set the value of maxOrd
     * @return  self
     */
    public function setMaxOrd(int $maxOrd): self
    {
        $this->maxOrd = $maxOrd;
        return $this;
    }
}

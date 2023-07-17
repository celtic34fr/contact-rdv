<?php

namespace Celtic34fr\ContactRendezVous\FormEntity;

class CalEventItems
{
    private ?array $items;

    /**
     * @return array|null
     */ 
    public function getItems(): ?array
    {
        return $this->items;
    }

    /**
     * @param array $items
     * @return CalEventItems
     */ 
    public function setItems(array $items): self
    {
        $this->items = $items;
        return $this;
    }

    /**
     * @param CalEventItem $item
     * @return CalEventItems
     */ 
    public function addItem(CalEventItem $item): self
    {
        $this->items[$item->getCle()] = $item;
        return $this;
    }

    /**
     * @param CalEventItem $item
     * @return CalEventItems|bool
     */ 
    public function removeItem(CalEventItem $item): mixed
    {
        if (array_key_exists($item->getCle(), $this->items)) {
            unset($this->items[$item->getCle()]);
            return $this;
        }
        return false;
    }
}

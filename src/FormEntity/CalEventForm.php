<?php

namespace Celtic34fr\ContactRendezVous\FormEntity;

use DateTime;
use Celtic34fr\ContactCore\Enum\EventEnums;
use phpDocumentor\Reflection\Types\Mixed_;

class CalEventForm
{
    private DateTime $time_at;
    private string $objet;
    private ?string $complements;
    private string $nature;
    private int $customer_id;
    private ?int $contact_id;

    /**
     * Get the value of time_at
     */ 
    public function getTimeAt(): DateTime
    {
        return $this->time_at;
    }

    /**
     * Set the value of time_at
     * @return  self
     */ 
    public function setTimeAt(DateTime $time_at): self
    {
        $this->time_at = $time_at;
        return $this;
    }

    /**
     * Get the value of objet
     */ 
    public function getObjet(): string
    {
        return $this->objet;
    }

    /**
     * Set the value of objet
     * @return  self
     */ 
    public function setObjet(string $objet): self
    {
        $this->objet = $objet;
        return $this;
    }

    /**
     * Get the value of complements
     */ 
    public function getComplements(): ?string
    {
        return $this->complements;
    }

    /**
     * Set the value of complements
     * @return  self
     */ 
    public function setComplements(string $complements): self
    {
        $this->complements = $complements;
        return $this;
    }

    /**
     * Get the value of nature
     */ 
    public function getNature(): string
    {
        return $this->nature;
    }

    /**
     * Set the value of nature
     * @return  mixed
     */ 
    public function setNature(string $nature): mixed
    {
        if (EventEnums::isValid($nature)) {
            $this->nature = $nature;
            return $this;
        }
        return false;
   }

    /**
     * Get the value of customerID
     */ 
    public function getCustomerId(): int
    {
        return $this->customer_id;
    }

    /**
     * Set the value of customer_id
     * @return  self
     */ 
    public function setCustomerId(int $customer_id)
    {
        $this->customer_id = $customer_id;
        return $this;
    }

    /**
     * Get the value of contact_id
     */ 
    public function getContactId(): ?int
    {
        return $this->contact_id;
    }

    /**
     * Set the value of contact_id
     * @return  self
     */ 
    public function setContactId(int $contact_id): self
    {
        $this->contact_id = $contact_id;
        return $this;
    }
}

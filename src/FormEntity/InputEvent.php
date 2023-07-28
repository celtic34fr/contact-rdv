<?php

namespace Celtic34fr\ContactRendezVous\FormEntity;

use DateTime;

class InputEvent
{
    private DateTime $start;
    private DateTime $end;
    private string $typeEvt;
    private string $title;
    private string $description;
    private bool $allDay;


    /**
     * @return dtring
     */
    public function getStart(): DateTime
    {
        return $this->start;
    }

    /**
     * @param DateTime $start
     * @return  InputEvent
     */
    public function setStart(DateTime $start): self
    {
        $this->start = $start;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getEnd(): DateTime
    {
        return $this->end;
    }

    /**
     * @param DateTime $end
     * @return  InputEvent
     */
    public function setEnd(DateTime $end): self
    {
        $this->end = $end;
        return $this;
    }

    /**
     * @return string
     */ 
    public function getTypeEvt(): string
    {
        return $this->typeEvt;
    }

    /**
     * @param string $typeEvt
     * @return  InputEvent
     */ 
    public function setTypeEvt(string $typeEvt): self
    {
        $this->typeEvt = $typeEvt;
        return $this;
    }

    /**
     * @return string
     */ 
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return  InputEvent
     */ 
    public function setTitle(string $title): self
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return string
     */ 
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return  InputEvent
     */ 
    public function setDescription(dtring $description): self
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return bool
     */ 
    public function getAllDay(): bool
    {
        return $this->allDay;
    }

    /**
     * @param bool $allDay
     * @return  InputEvent
     */ 
    public function setAllDay(bool $allDay): self
    {
        $this->allDay = $allDay;
        return $this;
    }
}

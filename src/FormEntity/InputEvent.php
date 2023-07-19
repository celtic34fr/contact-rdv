<?php

namespace Celtic34fr\ContactRendezVous\FormEntity;

class InputEvent
{
    private string $startBG;
    private ?string $hourBG;
    private string $startED;
    private ?string $hourED;
    private string $typeEvt;
    private string $title;
    private string $description;
    private bool $allDay;


    /**
     * @return dtring
     */
    public function getStartBG(): string
    {
        return $this->startBG;
    }

    /**
     * @param string $startBG
     * @return  InputEvent
     */
    public function setStartBG(string $startBG): self
    {
        $this->startBG = $startBG;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getHourBG(): ?string
    {
        return $this->hourBG;
    }

    /**
     * @param string $hourBG
     * @return  InputEvent
     */
    public function setHourBG(string $hourBG): self
    {
        $this->hourBG = $hourBG;
        return $this;
    }

    /**
     * @return dtring
     */
    public function getStartED(): string
    {
        return $this->startED;
    }

    /**
     * @param string $startED
     * @return  InputEvent
     */
    public function setStartED(string $startED): self
    {
        $this->startED = $startED;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getHourED(): ?string
    {
        return $this->hourED;
    }

    /**
     * @param string $hourED
     * @return  InputEvent
     */
    public function setHourED(string $hourED): self
    {
        $this->hourED = $hourED;
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

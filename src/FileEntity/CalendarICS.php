<?php

namespace Celtic34fr\ContactRendezVous\FileEntity;

use Celtic34fr\ContactRendezVous\Entity\CalEvent;
use DateTime;

class CalendarICS
{
    private DateTime $dateStart;
    private DateTime $dateEnd;
    private string $object;
    private string $place;
    private string $details;

    public function __construct(CalEvent $calEvent = null)
    {
        if ($calEvent) {
            $this->setDateStart($calEvent->getStartAt());
            $this->setDateEnd($calEvent->getEndAt());
            $this->setObject($calEvent->getObjet());
            $this->setPlace("par téléphone");
            $this->setDetails($calEvent->getComplements());
        }
    }

    public function getEventICS()
    {
        $ics = "";
        $ics .= "BEGIN:VEVENT\n";
        $ics .= "DTSTART:".$this->dateStart->format('Ymd')."T".$this->dateStart->format('His')."Z\n";
        $ics .= "DTEND:".$this->dateEnd->format('Ymd')."T".$this->dateEnd->format('His')."Z\n";
        $ics .= "SUMMARY:".$this->object."\n";
        $ics .= "LOCATION:".$this->place."\n";
        $ics .= "DESCRIPTION:".$this->details."\n";
        $ics .= "END:VEVENT\n";
        return $ics;
    }

    /**
     * Get the value of dateStart
     * @return DateTime|null
     */
    public function getDateStart(): DateTime
    {
        return $this->dateStart ?? null;
    }

    /**
     * Set the value of dateStart
     * 
     * @param DateTime $dateStart
     * @return CalendarICS
     */
    public function setDateStart(DateTime $dateStart): self
    {
        $this->dateStart = $dateStart;
        return $this;
    }

    /**
     * Get the value of dateEnd
     * 
     * @return DateTime|null
     */
    public function getDateEnd(): mixed
    {
        return $this->dateEnd ?? null;
    }

    /**
     * Set the value of dateEnd
     * 
     * @param DateTime $dateEnd
     * @return CalendarICS
     */
    public function setDateEnd(DateTime $dateEnd): self
    {
        $this->dateEnd = $dateEnd;
        return $this;
    }

    /**
     * Get the value of object
     * 
     * @return string|null
     */
    public function getObject(): mixed
    {
        return $this->object ?? null;
    }

    /**
     * Set the value of object
     * 
     * @param string $object
     * @return CalendarICS
     */
    public function setObject(string $object): self
    {
        $this->object = $object;
        return $this;
    }

    /**
     * Get the value of place
     * 
     * @return string|null
     */
    public function getPlace(): mixed
    {
        return $this->place ?? null;
    }

    /**
     * Set the value of place
     * 
     * @param string|null
     * @return CalendarICS
     */
    public function setPlace(string $place): self
    {
        $this->place = $place;
        return $this;
    }

    /**
     * Get the value of details
     * 
     * @return string|null
     */
    public function getDetails(): mixed
    {
        return $this->details ?? null;
    }

    /**
     * Set the value of details
     * 
     * @param string $details
     * @return CalendarICS
     */
    public function setDetails(string $details): self
    {
        $this->details = $details;
        return $this;
    }
}
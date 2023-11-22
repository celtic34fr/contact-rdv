<?php

namespace Celtic34fr\ContactRendezVous\FileEntity;

use Celtic34fr\ContactCore\Entity\CliInfos;
use Celtic34fr\ContactCore\Enum\StatusEnums;
use Celtic34fr\ContactRendezVous\Entity\CalEvent;
use DateTime;
use Spatie\IcalendarGenerator\Components\Event;
use Spatie\IcalendarGenerator\Enums\Classification;
use Spatie\IcalendarGenerator\Enums\EventStatus;

class CalendarICS
{
    private DateTime $dateStart;
    private DateTime $dateEnd;
    private string $object;
    private string $place;
    private string $details;
    private bool $allday;
    private CliInfos $invite;
    private string $status;

    public function __construct(CalEvent $calEvent = null)
    {
        if ($calEvent) {
            $this->setDateStart($calEvent->getStartAt());
            $this->setDateEnd($calEvent->getEndAt());
            $this->setObject($calEvent->getObjet());
            $this->setPlace("par téléphone");
            $this->setDetails($calEvent->getComplements());
            $this->setAllday($calEvent->getAllDay());
            $this->setInvite($calEvent->getInvite());
            $this->setStatus($calEvent->getStatus());
        }
    }

    public function getEventICS()
    {
        $uid = uniqid("calevent", true);
        $created =  new DateTime('now');
        $organizer = "";

        $ics = Event::create()
            ->name($this->object)
            ->description($this->details)
            ->uniqueIdentifier($uid)
            ->createdAt($created)
            ->startsAt($this->dateStart)
            ->endsAt($this->dateEnd)
            ->organizer($organizer)
        ;

        /** traitement des participant ici 1 seul */
        $ics->attendee($this->invite->getClient()->getCourriel(), $this->invite->getFullName());

        if ($this->isAllday()) {
            $ics->fullDay();
        }

        $ics->classification(Classification::public());

        /** gestion du status de l'événement dans le calendrier */
        switch($this->getStatus()) {
            case StatusEnums::WaitResponse->_toString():
                $ics->status(EventStatus::tentative());
                break;
            case StatusEnums::Accepted->_toString():
                $ics->status(EventStatus::confirmed());
                break;
            case StatusEnums::Refused->_toString():
                $ics->status(EventStatus::cancelled());
                break;
            case StatusEnums::Reported->_toString():
                $ics->status(EventStatus::tentative());
                break;
        }

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

    /**
     * Get the value of allday
     * 
     * @return bool
     */
    public function isAllday(): bool
    {
        return $this->allday ?? false;
    }

    /**
     * Set the value of allday
     * 
     * @param bool $allday
     * @return CalendarICS
     */
    public function setAllday(bool $allday): self
    {
        $this->allday = $allday;
        return $this;
    }

    /**
     * Get the value of invite
     */
    public function getInvite(): CliInfos
    {
        return $this->invite;
    }

    /**
     * Set the value of invite
     */
    public function setInvite(CliInfos $invite): self
    {
        $this->invite = $invite;

        return $this;
    }

    /**
     * Get the value of status
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * Set the value of status
     */
    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }
}
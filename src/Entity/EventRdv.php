<?php

namespace Celtic34fr\ContactRendezVous\Entity;

use Celtic34fr\CalendarCore\Entity\CalEvent;
use Celtic34fr\CalendarCore\Enum\ClassificationEnums;
use Celtic34fr\CalendarCore\Enum\StatusEnums;
use Celtic34fr\ContactCore\Entity\CliInfos;
use Celtic34fr\ContactRendezVous\Repository\EventRdvRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class EventRdv link between Calendar and Prospect/Customer 
 * 
 */
#[ORM\Entity(repositoryClass: EventRdvRepository::class)]
#[ORM\Table('events_rdv')]
class EventRdv
{
    #[ORM\OneToOne(targetEntity: CompteRendu::class, inversedBy: 'rendezVous')]
    #[ORM\JoinColumn(name: 'compte_rendu_id', referencedColumnName: 'id')]
    private ?CompteRendu $compte_rendu = null;

    #[ORM\OneToOne(targetEntity: CalEvent::class)]
    #[ORM\JoinColumn(name: 'calEvent_id', referencedColumnName: 'id')]
    private CalEvent|null $calEvent = null;

    #[ORM\ManyToMany(targetEntity: CliInfos::class)]
    #[ORM\JoinColumn(name: 'eventrdv_id', referencedColumnName: 'id', nullable: false)]
    #[ORM\InverseJoinColumn(name: 'cliinfos_id', referencedColumnName: 'id', unique: true)]
    #[ORM\JoinTable(name: 'cliinfos_eventrdv')]
    private ?CliInfos $invite = null;


    /**
     * @return CompteRendu|null
     */
    public function getCompteRendu(): ?CompteRendu
    {
        return $this->compte_rendu;
    }

    /**
     * @param CompteRendu|null $compte_rendu
     * @return self
     */
    public function setCompteRendu(?CompteRendu $compte_rendu): self
    {
        $this->compte_rendu =$compte_rendu;
        return $this;
    }

    /**
     * @return CalEvent|null
     */
    public function getCalEvent(): ?CalEvent
    {
        return $this->calEvent;
    }

    /**
     * @param CalEvent $calEvent
     * @return self
     */
    public function setCalEvent(CalEvent $calEvent): self
    {
        $this->calEvent = $calEvent;
        return $this;
    }

    /**
     * @return CliInfos|null
     */
    public function getInvite(): ?CliInfos
    {
        return $this->invite;
    }

    /**
     * @param CliInfos|null $invite
     * @return self
     */
    public function setInvite(?CliInfos $invite): self
    {
        $this->invite = $invite;
        return $this;
    }


    public function generateCalEvent(array $event): CalEvent
    {
        $calEvent = new CalEvent();

        $calEvent->setCreatedAt(new DateTime('now'));
        $calEvent->setStartAt(new DateTime($event['startAt']));
        if (array_key_exists('endAt', $event)) $calEvent->setEndAt(new DateTime($event['endAt']));
        if (array_key_exists('subject', $event)) $calEvent->setSubject($event['subject']);
        if (array_key_exists('details', $event)) $calEvent->setDetails($event('details'));
        $calEvent->setNature($event['nature']);
        $calEvent->setAllDay((bool) $event['allDay']);
        $calEvent->setStatus(StatusEnums::NeedsAction->_toString());
        $calEvent->setClassification(ClassificationEnums::Public->_toString());
        if (array_key_exists('location', $event)) $calEvent->setLocation($event['location']);
        if (array_key_exists('timezone', $event)) $calEvent->setTimezone($event['timezone']);
        if (array_key_exists('frequence', $event)) $calEvent->setFrequence($event['frequence']);
        if (array_key_exists('attentees', $event)) {
            foreach ($event['attentees'] as $attentee) {
                $calEvent->addAttendee($attentee);
            }
        }
        if (array_key_exists('organizer', $event)) $calEvent->setOrganizer($event['organizer']);
        if (array_key_exists('alarms', $event)) {
            foreach ($event['alarms'] as $alarm) {
                $calEvent->addAlarm($alarm);
            }
        }
        if (array_key_exists('dtStamp', $event)) $calEvent->setDtStamp(new DateTime($event['dtStamp']));
        if (array_key_exists('priority', $event)) $calEvent->setPriority($event['priority']);
        if (array_key_exists('seq', $event)) $calEvent->setSeq($event['seq']);
        if (array_key_exists('transp', $event)) $calEvent->setTransp($event['transp']);
        if (array_key_exists('url', $event)) $calEvent->setUrl($event['url']);
        if (array_key_exists('duration', $event)) $calEvent->setDuration($event['duration']);
        if (array_key_exists('attachs', $event)) {
            foreach ($event['attachs'] as $attach) {
                $calEvent->addAttach($attach);
            }
        }
        if (array_key_exists('categories', $event)) {
            foreach ($event['categories'] as $category) {
                $calEvent->addCategory($category);
            }
        }
        if (array_key_exists('contact', $event)) $calEvent->setContact($event['contact']);
        if (array_key_exists('exDate', $event)) $calEvent->setExDate(new Datetime($event['url']));
        if (array_key_exists('rStatus', $event)) $calEvent->setRStatus($event['rStatus']);
        if (array_key_exists('related', $event)) $calEvent->setRelated($event['related']);
        if (array_key_exists('resources', $event)) $calEvent->setResources($event['resources']);
        if (array_key_exists('rDate', $event)) $calEvent->setRDate($event['rDate']);

        return $calEvent;
    }
}
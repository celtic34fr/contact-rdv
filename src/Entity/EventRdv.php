<?php

namespace Celtic34fr\ContactRendezVous\Entity;

use Celtic34fr\CalendarCore\Entity\Attendee;
use Celtic34fr\CalendarCore\Entity\CalEvent;
use Celtic34fr\CalendarCore\Entity\Contact;
use Celtic34fr\CalendarCore\Entity\Organizer;
use Celtic34fr\CalendarCore\Entity\Parameter;
use Celtic34fr\CalendarCore\Enum\ClassificationEnums;
use Celtic34fr\CalendarCore\Enum\StatusEnums;
use Celtic34fr\CalendarCore\Model\EventAlarm;
use Celtic34fr\CalendarCore\Model\EventLocation;
use Celtic34fr\CalendarCore\Model\EventRepetition;
use Celtic34fr\ContactCore\Entity\CliInfos;
use Celtic34fr\ContactRendezVous\Repository\EventRdvRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
     * @return self
     */
    public function setInvite(?CliInfos $invite): self
    {
        $this->invite = $invite;

        if (!$this->calEvent) {
            $this->calEvent = new CalEvent();
        }
        $attendee = new Attendee();
        $attendee->setFullname($invite->getFullname());
        $attendee->setEmail($invite->getClient()->getCourriel());

        $this->calEvent->addAttendee($attendee);

        return $this;
    }


    /**
     * through pass method EventRdv to CalEvent
     */

    /**
     * Creation's Date of Event 
     * @return DateTime
     */
    public function getCreatedAt(): DateTime
    {
        if (!$this->calEvent) $this->calEvent = new CalEvent();
        return $this->calEvent->getCreatedAt();
    }

    /**
     * Set the value of Creation's Date of Event
     * @param DateTime $created_at
     * @return self
     */
    public function setCreatedAt(DateTime $created_at): self
    {
        if (!$this->calEvent) $this->calEvent = new CalEvent();
        $this->calEvent->setCreatedAt($created_at);

        return $this;
    }

    /**
     * LastUpdated's Date of Event
     * @return DateTime|null
     */
    public function getLastUpdated(): ?DateTime
    {
        if (!$this->calEvent) $this->calEvent = new CalEvent();
        return $this->calEvent->getLastUpdated();
    }

    /**
     * set the value of LastUpdated's Date of Event
     * @param DateTime|null $last_updated
     * @return self
     */
    public function setLastUpdated(?DateTime $last_updated): self
    {
        if (!$this->calEvent) $this->calEvent = new CalEvent();
        $this->calEvent->setLastUpdated($last_updated);
        return $this;
    }

    /**
     * Start's Date of Event
     * @return DateTime|null
     */
    public function getStartAt(): ?DateTime
    {
        if (!$this->calEvent) $this->calEvent = new CalEvent();
        return $this->calEvent->getStartAt();
    }

    /**
     * set th value of Start's Date of Event
     * @param DateTime $start_at
     * @return self
     */
    public function setStartAt(DateTime $start_at): self
    {
        if (!$this->calEvent) $this->calEvent = new CalEvent();
        $this->calEvent->setStartAt($start_at);
        return $this;
    }

    /**
     * End's Date of Event
     * @return DateTime|null
     */
    public function getEndAt(): ?DateTime
    {
        if (!$this->calEvent) $this->calEvent = new CalEvent();
        return $this->calEvent->getEndAt();
    }

    /**
     * set the value Start's Date of Event
     * @param DateTime $end_at
     * @return self
     */
    public function setEndAt(DateTime $end_at): self
    {
        if (!$this->calEvent) $this->calEvent = new CalEvent();
        $this->calEvent->setEndAt($end_at);
        return $this;
    }

    /**
     * Object or Subject of Event
     * @return string|null
     */
    public function getSubject(): ?string
    {
        if (!$this->calEvent) $this->calEvent = new CalEvent();
        return $this->calEvent->getSubject();
    }

    /**
     * set the value of Object or Subject of Event
     * @param string $subject
     * @return self
     */
    public function setSubject(string $subject): self
    {
        if (!$this->calEvent) $this->calEvent = new CalEvent();
        $this->calEvent->setSubject($subject);
        return $this;
    }

    /**
     * get the value of Details of Event
     * @return string|null
     */
    public function getDetails(): ?string
    {
        if (!$this->calEvent) $this->calEvent = new CalEvent();
        return $this->calEvent->getDetails();
    }

    /**
     * set the value of Details of Event
     * @param string|null $details
     * @return self
     */
    public function setDetails(?string $details): self
    {
        if (!$this->calEvent) $this->calEvent = new CalEvent();
        $this->calEvent->setDetails($details);
        return $this;
    }

    /**
     * Nature or Type of Event
     * @return Parameter|null
     */
    public function getNature(): ?Parameter
    {
        if (!$this->calEvent) $this->calEvent = new CalEvent();
        return $this->calEvent->getNature();
    }

    /**
     * set the value of Nature or Type of Event
     * @param Parameter $nature
     * @return self
     */
    public function setNature(Parameter $nature): self
    {
        if (!$this->calEvent) $this->calEvent = new CalEvent();
        $this->calEvent->setNature($nature);
        return $this;
    }

    /**
     * Get the value of bg_color
     * @return string|null
     */ 
    public function getBgColor(): ?string
    {
        if (!$this->calEvent) $this->calEvent = new CalEvent();
        return $this->calEvent->getBgColor();
    }

    /**
     * Set the value of bg_color
     * @param string $bg_color
     * @return  self
     */ 
    public function setBgColor(string $bg_color): self
    {
        if (!$this->calEvent) $this->calEvent = new CalEvent();
        $this->calEvent->setBgColor($bg_color);
        return $this;
    }

    /**
     * Get the value of bd_color
     * @return string|null
     */ 
    public function getBdColor(): ?string
    {
        if (!$this->calEvent) $this->calEvent = new CalEvent();
        return $this->calEvent->getBdColor();
    }

    /**
     * Set the value of bd_color
     * @param string $bd_color
     * @return self
     */ 
    public function setBdColor(string $bd_color): self
    {
        if (!$this->calEvent) $this->calEvent = new CalEvent();
        $this->calEvent->setBdColor($bd_color);
        return $this;
    }

    /**
     * Get the value of tx_color
     * @return string|null
     */ 
    public function getTxColor(): ?string
    {
        if (!$this->calEvent) $this->calEvent = new CalEvent();
        return $this->calEvent->getTxColor();
    }

    /**
     * Set the value of tx_color
     * @param string $tx_color
     * @return self
     */ 
    public function setTxColor(string $tx_color): self
    {
        if (!$this->calEvent) $this->calEvent = new CalEvent();
        $this->calEvent->setTxColor($tx_color);
        return $this;
    }

    /**
     * Get the value of all_day
     * @return bool
     */ 
    public function getAllDay(): bool
    {
        if (!$this->calEvent) $this->calEvent = new CalEvent();
        return (bool) $this->calEvent->getAllDay();
    }

    /**
     * Set the value of all_day
     * @param bool $all_day
     * @return self
     */ 
    public function setAllDay(bool $all_day): self
    {
        if (!$this->calEvent) $this->calEvent = new CalEvent();
        $this->calEvent->setAllDay((bool) $all_day);
        return $this;
    }

    /**
     * Get the value of status of the event
     * @return string
     */
    public function getStatus(): string
    {
        if (!$this->calEvent) $this->calEvent = new CalEvent();
        return $this->calEvent->getStatus();
    }

    /**
     * Set the value of status
     * @param string $status
     * @return self|bool
     */
    public function setStatus(string $status): mixed
    {
        if (!$this->calEvent) $this->calEvent = new CalEvent();
        if (StatusEnums::isValidVevent($status)) {
            $this->calEvent->setStatus($status);
            return $this;
        }
        return false;
    }

    /**
     * Get the value of uid
     * @return string|null
     */
    public function getUid(): ?string
    {
        if (!$this->calEvent) $this->calEvent = new CalEvent();
        return $this->calEvent->getUid();
    }

    /**
     * set the value of uid
     * @param string $uid
     * @return self
     */
    public function setUid(string $uid): self
    {
        if (!$this->calEvent) $this->calEvent = new CalEvent();
        $this->calEvent->setUid($uid);
        return $this;
    }

    /**
     * Get the value of Classification
     * @return string|null
     */
    public function getClassification(): ?string
    {
        if (!$this->calEvent) $this->calEvent = new CalEvent();
        return $this->calEvent->getClassification();
    }

    /**
     * set the value of Classification
     * @param string $classification
     * @return self|bool
     */
    public function setClassification(string $classification): mixed
    {
        if (!$this->calEvent) $this->calEvent = new CalEvent();
        if (ClassificationEnums::isValid($classification)) {
            $this->calEvent->setClassification($classification);
            return $this;
        }
        return false;
    }

    /**
     * Get the value of location
     * @return EventLocation|null
     */
    public function getLocation(): ?EventLocation
    {
        if (!$this->calEvent) $this->calEvent = new CalEvent();
        return $this->calEvent->getLocation();
    }

    /**
     * set the value of location
     * @param EventLocation $location
     * @return self
     */
    public function setLocation(EventLocation $location): self
    {
        if (!$this->calEvent) $this->calEvent = new CalEvent();
        $this->calEvent->setLocation($location);

        return $this;
    }

    /**
     * Get the value of Timezone
     * @return string|null
     */
    public function getTimezone(): ?string
    {
        if (!$this->calEvent) $this->calEvent = new CalEvent();
        return $this->calEvent->getTimezone();
    }

    /**
     * Set the value of Timezone
     * @param string $timezone
     * @return self
     */
    public function setTimezone(string $timezone): self
    {
        if (!$this->calEvent) $this->calEvent = new CalEvent();
        $this->calEvent->setTimezone($timezone);
        return $this;
    }

    /**
     * get the Repetition of Event object
     * @return EventRepetition|null
     */
    public function getFrequence(): ?EventRepetition
    {
        if (!$this->calEvent) $this->calEvent = new CalEvent();
        return $this->calEvent->getFrequence();
    }

    /**
     * set the Repetition of Event object
     * @param EventRepetition $frequence
     * @return self
     */
    public function setFrequence(EventRepetition $frequence): self
    {
        if (!$this->calEvent) $this->calEvent = new CalEvent();
        $this->calEvent->setFrequence($frequence);
        return $this;
    }

    /**
     * Get the value of alarms
     * get Persons, Contacts, Prospects, Customers
     * @return Collection|EventAlarm[]|null
     */
    public function getAlarms(): ?Collection
    {
        if (!$this->calEvent) $this->calEvent = new CalEvent();
        return $this->calEvent->getAlarms();
    }

    /**
     * @return bool
     */
    public function emptyAlarms(): bool
    {
        if (!$this->calEvent) $this->calEvent = new CalEvent();
        return $this->calEvent->emptyAlarms();
    }

    /**
     * add one Alamr to the Event
     * @param EventAlarm $alarm
     * @return self
     */
    public function addAlarm(EventAlarm $alarm): self
    {
        if (!$this->calEvent) $this->calEvent = new CalEvent();
        if (!$this->calEvent->getAlarms()->contains($alarm)) {
            $this->calEvent->addAlarm($alarm);
        }
        return $this;
    }

    /**
     * remove one Alarm to the Event
     * @param EventAlarm $alarm
     * @return self
     */
    public function removeAlarm(EventAlarm $alarm): self
    {
        if (!$this->calEvent) $this->calEvent = new CalEvent();
        $this->calEvent->getAlarms()->removeElement($alarm);
        return $this;
    }

    /**
     * Set the value of alarms
     * @param Collection $alarms
     * @return self
     */
    public function setAlarms(?Collection $alarms): self
    {
        if (!$this->calEvent) $this->calEvent = new CalEvent();
        $this->calEvent->setAlarms($alarms);
        return $this;
    }

    /**
     * Get the value of dt_stamp
     * @return DateTime|null
     */
    public function getDtStamp(): ?DateTime
    {
        if (!$this->calEvent) $this->calEvent = new CalEvent();
        return $this->calEvent->getDtStamp();
    }

    /**
     * @return boolean
     */
    public function emptyDtStamp(): bool
    {
        if (!$this->calEvent) $this->calEvent = new CalEvent();
        return $this->calEvent->emptyDtStamp();
    }

    /**
     * Set the value of dt_stamp
     * @param DateTime $dt_stamp
     * @return self
     */
    public function setDtStamp(DateTime $dt_stamp): self
    {
        if (!$this->calEvent) $this->calEvent = new CalEvent();
        $this->calEvent->setDtStamp($dt_stamp);
        return $this;
    }

    /**
     * Get the value of priority
     * @return string|null
     */
    public function getPriority(): ?string
    {
        if (!$this->calEvent) $this->calEvent = new CalEvent();
        return $this->calEvent->getPriority();
    }

    /**
     * @return boolean
     */
    public function emptyPriority(): bool
    {
        if (!$this->calEvent) $this->calEvent = new CalEvent();
        return $this->calEvent->emptyPriority();
    }

    /**
     * Set the value of priority
     * @param string $priority
     * @return self
     */
    public function setPriority(string $priority): self
    {
        if (!$this->calEvent) $this->calEvent = new CalEvent();
        $this->calEvent->setPriority($priority);
        return $this;
    }

    /**
     * Get the value of seq
     * @return int|null
     */
    public function getSeq(): ?int
    {
        if (!$this->calEvent) $this->calEvent = new CalEvent();
        return $this->calEvent->getSeq();
    }

    /**
     * @return boolean
     */
    public function emptySeq(): bool
    {
        if (!$this->calEvent) $this->calEvent = new CalEvent();
        return $this->calEvent->emptySeq();
    }

    /**
     * Set the value of seq
     * @param int $seq
     * @return self
     */
    public function setSeq(string $seq): self
    {
        if (!$this->calEvent) $this->calEvent = new CalEvent();
        $this->calEvent->setSeq($seq);
        return $this;
    }

    /**
     * Get the value of transp
     * @return string|null
     */
    public function getTransp(): ?string
    {
        if (!$this->calEvent) $this->calEvent = new CalEvent();
        return $this->calEvent->getTransp();
    }

    /**
     * @return boolean
     */
    public function emptyTransp(): bool
    {
        if (!$this->calEvent) $this->calEvent = new CalEvent();
        return $this->calEvent->emptyTransp();
    }

    /**
     * Set the value of transp
     * @param string $transp
     * @return self
     */
    public function setTransp(string $transp): self
    {
        if (!$this->calEvent) $this->calEvent = new CalEvent();
        $this->setTransp($transp);
        return $this;
    }

    /**
     * Get the value of url
     * @return string|null
     */
    public function getUrl(): ?string
    {
        if (!$this->calEvent) $this->calEvent = new CalEvent();
        return $this->getUrl();
    }

    /**
     * @return boolean
     */
    public function emptyUrl(): bool
    {
        if (!$this->calEvent) $this->calEvent = new CalEvent();
        return $this->calEvent->emptyUrl();
    }

    /**
     * Set the value of url
     * @param string $url
     * @return self
     */
    public function setUrl(string $url): self
    {
        if (!$this->calEvent) $this->calEvent = new CalEvent();
        $this->calEvent->setUrl($url);
        return $this;
    }

    /**
     * Get the value of recur_id
     * @return string|null
     */
    public function getRecurId(): ?string
    {
        if (!$this->calEvent) $this->calEvent = new CalEvent();
        return $this->calEvent->getRecurId();
    }

    /**
     * @return boolean
     */
    public function emptyRecurId(): bool
    {
        if (!$this->calEvent) $this->calEvent = new CalEvent();
        return $this->calEvent->emptyRecurId();
    }

    /**
     * Set the value of recur_id
     * @param string $recur_id
     * @return self
     */
    public function setRecurId(string $recur_id): self
    {
        if (!$this->calEvent) $this->calEvent = new CalEvent();
        $this->calEvent->setRecurId($recur_id);
        return $this;
    }

    /**
     * Get the value of duration
     * @return string|null
     */
    public function getDuration(): ?string
    {
        if (!$this->calEvent) $this->calEvent = new CalEvent();
        return $this->calEvent->getDuration();
    }

    /**
     * @return boolean
     */
    public function emptyDuration(): bool
    {
        if (!$this->calEvent) $this->calEvent = new CalEvent();
        return $this->calEvent->emptyDuration();
    }

    /**
     * Set the value of duration
     * @param string $duration
     * @return self
     */
    public function setDuration(string $duration): self
    {
        if (!$this->calEvent) $this->calEvent = new CalEvent();
        $this->calEvent->setDuration($duration);
        return $this;
    }

    /**
     * get the Attachs of the Event
     * @return Collection<int, array>|null
     */
    public function getAttachs(): ?Collection
    {
        if (!$this->calEvent) $this->calEvent = new CalEvent();
        return $this->calEvent->getAttachs();
    }

    /**
     * @return boolean
     */
    public function emptyAttachs(): bool
    {
        if (!$this->calEvent) $this->calEvent = new CalEvent();
        return $this->calEvent->emptyAttachs();
    }

    /**
     * add 1 attach to the Attachs of the Event
     * @param array $attach
     * @return self
     */
    public function addAttach(array $attach): self
    {
        if (!$this->calEvent) $this->calEvent = new CalEvent();
        if (!$this->calEvent->getAttachs()->contains($attach)) {
            $this->calEvent->addAttach($attach);
        }
        return $this;
    }

    /**
     * remove 1 attach if exist in Attachs of the Event
     * @param array $attach
     * @return self|bool
     */
    public function removeAttach(array $attach): mixed
    {
        if (!$this->calEvent) $this->calEvent = new CalEvent();
        if ($this->calEvent->getAttachs()->removeElement($attach)) {
            return $this;
        }
        return false;
    }

    /**
     * get the Categories of the Event
     * @return Collection<int, string>|null
     */
    public function getCategories(): ?Collection
    {
        if (!$this->calEvent) $this->calEvent = new CalEvent();
        return $this->calEvent->getCategories();
    }

    /**
     * add 1 category to the Categories of the Event
     * @param string $category
     * @return self
     */
    public function addCategory(string $category): self
    {
        if (!$this->calEvent) $this->calEvent = new CalEvent();
        if (!$this->calEvent->getCategories()->contains($category)) {
            $this->calEvent->addCategory($category);
        }
        return $this;
    }

    /**
     * @return boolean
     */
    public function emptyCategories(): bool
    {
        return $this->calEvent->emptyCategories();
    }

    /**
     * remove 1 category if exist in Categories of the Event
     * @param string $category
     * @return self|bool
     */
    public function removeCategory(string $category): mixed
    {
        if (!$this->calEvent) $this->calEvent = new CalEvent();
        if ($this->calEvent->getCategories()->removeElement($category)) {
            return $this;
        }
        return false;
    }

    /**
     * Get the value of contact
     * @return Contact|null
     */
    public function getContact(): ?Contact
    {
        if (!$this->calEvent) $this->calEvent = new CalEvent();
        return $this->calEvent->getContact();
    }

    /**
     * @return boolean
     */
    public function emptyContact(): bool
    {
        return $this->calEvent->emptyContact();
    }

    /**
     * Set the value of contact
     * @param Contact $contact
     * @return self
     */
    public function setContact(Contact $contact): self
    {
        if (!$this->calEvent) $this->calEvent = new CalEvent();
        $this->calEvent->setContact($contact);
        return $this;
    }

    /**
     * Get the value of ex_date
     * @return DateTime|null
     */
    public function getExDate(): ?DateTime
    {
        if (!$this->calEvent) $this->calEvent = new CalEvent();
        return $this->calEvent->getExDate();
    }

    /**
     * @return boolean
     */
    public function emptyExDate(): bool
    {
        if (!$this->calEvent) $this->calEvent = new CalEvent();
        return $this->calEvent->emptyExDate();
    }

    /**
     * Set the value of ex_date
     * @param DateTime $ex_date
     * @return self
     */
    public function setExDate(DateTime $ex_date): self
    {
        if (!$this->calEvent) $this->calEvent = new CalEvent();
        $this->calEvent->setExDate($ex_date);
        return $this;
    }

    /**
     * Get the value of r_status
     * @return string|null
     */
    public function getRStatus(): ?string
    {
        if (!$this->calEvent) $this->calEvent = new CalEvent();
        return $this->calEvent->getRStatus();
    }

    /**
     * @return boolean
     */
    public function emptyRStatus(): bool
    {
        if (!$this->calEvent) $this->calEvent = new CalEvent();
        return $this->calEvent->emptyRStatus();
    }

    /**
     * Set the value of r_status
     * @param string $r_status
     * @return self
     */
    public function setRStatus(string $r_status): self
    {
        if (!$this->calEvent) $this->calEvent = new CalEvent();
        $this->calEvent->setRStatus($r_status);
        return $this;
    }

    /**
     * Get the value of related
     * @return string |null
     */
    public function getRelated(): ?string
    {
        if (!$this->calEvent) $this->calEvent = new CalEvent();
        return $this->calEvent->getRelated();
    }

    /**
     * @return boolean
     */
    public function emptyRelated(): bool
    {
        if (!$this->calEvent) $this->calEvent = new CalEvent();
        return $this->calEvent->emptyRelated();
    }

    /**
     * Set the value of related
     * @param string $related
     * @return self
     */
    public function setRelated(string $related): self
    {
        if (!$this->calEvent) $this->calEvent = new CalEvent();
        $this->calEvent->setRelated($related);
        return $this;
    }

    /**
     * Get the value of resources
     * @return string|null
     */
    public function getResources(): ?string
    {
        if (!$this->calEvent) $this->calEvent = new CalEvent();
        return $this->calEvent->getResources();
    }

    /**
     * @return boolean
     */
    public function emptyResources(): bool
    {
        if (!$this->calEvent) $this->calEvent = new CalEvent();
        return $this->calEvent->emptyResources();
    }

    /**
     * Set the value of resources
     * @param string $resources
     * @return self
     */
    public function setResources(string $resources): self
    {
        if (!$this->calEvent) $this->calEvent = new CalEvent();
        $this->calEvent->setResources($resources);
        return $this;
    }

    /**
     * Get the value of r_date
     * @return DateTime|null
     */
    public function getRDate(): ?DateTime
    {
        if (!$this->calEvent) $this->calEvent = new CalEvent();
        return $this->calEvent->getRDate();
    }

    /**
     * @return boolean
     */
    public function emptyRDate(): bool
    {
        return $this->calEvent->emptyRDate();
    }

    /**
     * Set the value of r_date
     * @param DateTime $r_date
     * @return self
     */
    public function setRDate(DateTime $r_date): self
    {
        $this->calEvent->setRDate($r_date);
        return $this;
    }


    public function generateCalEventByArray(array $event): CalEvent
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
        if (array_key_exists('organizer', $event) && $event['organizer'] instanceof Organizer)
            $calEvent->setOrganizer($event['organizer']);
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
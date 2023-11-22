<?php

namespace Celtic34fr\ContactRendezVous\Service;

use Celtic34fr\ContactRendezVous\Entity\CalEvent;
use Celtic34fr\ContactRendezVous\FileEntity\CalendarICS;
use Celtic34fr\ContactRendezVous\Service\iCalEasyReader;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Spatie\IcalendarGenerator\Components\Calendar as SpatieCalendar;
use Spatie\IcalendarGenerator\Components\Timezone;
use Spatie\IcalendarGenerator\Components\TimezoneEntry;
use Spatie\IcalendarGenerator\Enums\TimezoneEntryType;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * Calendar Service Class
 * 
 * manage calendar in this extension
 * 
 * -> save/restore/init Calendar Event table (CaleEvent) to/with ICS files
 */
class Calendar
{
    public function __construct(private EntityManagerInterface $entityManager, private KernelInterface $appKernel)
    {
        
    }

    /**
     * Method to created file with the content of CalEvent table into ICS format
     *
     * @param string $filename
     * @return void
     */
    public function exportCalendar(string $filename)
    {
        $allEvent = $this->entityManager->getRepository(CalEvent::class)->findAll();
        $filesystem = new Filesystem();
        $allEventICS = $this->transformEventsICS($allEvent);
        $projectDir = $this->appKernel->getProjectDir();

        // écrire dans fichier
        $destination = $projectDir . "/public/download";
        if (!$filesystem->exists($destination)) {
            $filesystem->mkdir($destination);
             $filesystem->chgrp($destination, 'www-data', true);
            $filesystem->chmod($destination, 0777);
        } 
        $ficin= $destination . "exportICS".(new DateTime('now'))->format("YmdHis").".ics";
        $monfic = fopen($ficin, "w");
        fwrite($monfic, $allEventICS);
        fclose($monfic);
    }

    public function importCalendar(string $filename)
    {
        if (file_exists($filename)) {
            $calendar = new iCalEasyReader();
            $calendar->load(file_get_contents($filename));

            $events = $calendar['VEVENT'];
            if ($events) {
                foreach ($events as $event) {
                    $uid = $event['UID'] ?? "";
                    $created = array_key_exists('CREATED', $event) ? $this->extractDate($event['CREATED']) : new DateTime('now');
                    $lastUpdated = array_key_exists('LAST-MODIFIED', $event) ? $this->extractDate($event['LAST-MODIFIED']) : null;
                    $location = array_key_exists('LOCATION', $event) ? $event['LOCATION'] : null;
                    $description = array_key_exists('DESCRIPTION', $event) ? $event['DESCRIPTION'] : null;

                    $dtStart = $this->extractDate($event['DTSTART']);
                    $dtEnd = $this->extractDate($event['DTEND']);
                    $summary = $event['SUMMARY'];

                    if (array_key_exists('UID', $event)) {
                        $calEvent = $this->entityManager->getRepository(CelEvent::class)->findOneBy(['uid' => $event['UID']]);
                    }
                    if (!isset($calEvent) || !$calEvent) {
                        $calEvent = new CalEvent();
                    }
                    
                    /** @var CalEvent $calEvent */
                    $calEvent->setCreatedAt($created);
                    $calEvent->setStartAt($dtStart);
                    $calEvent->setEndAt($dtEnd);
                    $calEvent->setObjet($summary);
                    $calEvent->setComplements($description);
                    if ($uid) $calEvent->setUid($uid);
                    if ($lastUpdated) $calEvent->setLastUpdated($lastUpdated)
                }
            }
        }
        return false;
    }

    /**
     * format content of ICS file
     *
     * @param array $allEvent
     * @return string
     */
    private function transformEventsICS(array $allEvent): string
    {
        $calendar = SpatieCalendar::create('Extraction Calendars Events')
                        ->description('Events and meetings with custommers or contacts')
                        ;
        $timeZoneEntry = TimezoneEntry::create(
            TimezoneEntryType::daylight(),
            new DateTime(),
            "+00:00",
            "-02:00"
        );
        $timezone = Timezone::create('Europe/Paris')
            ->entry($timeZoneEntry);
        $calendar->timezone($timezone);

        /** @var CalEvent $event */
        foreach($allEvent as $event) {
            $eventIcs = new CalendarICS($event);
            $calendar->event($eventIcs->getEventICS());
        }
        return $calendar->get();
    }

    private function extractDate($eventDate): DateTime
    {
        if (is_array($eventDate)) {
            $dtStart = date_create_from_format('Ymd', $eventDate['value']);
        } elseif (substr($eventDate,8,1) == 'T') {
            $dtstart = substr($eventDate, 0, 8).substr($eventDate, 9, 6);
            $dtStart = date_create_from_format('YmdHis', $dtstart);
        }
        return $dtStart;
    }
}
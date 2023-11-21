<?php

namespace Celtic34fr\ContactRendezVous\Service;

use Celtic34fr\ContactRendezVous\Entity\CalEvent;
use Celtic34fr\ContactRendezVous\FileEntity\CalendarICS;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Spatie\IcalendarGenerator\Components\Calendar;
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
     * Method to save the content of CalEvent table into ICS File
     *
     * @param string $filename
     * @return void
     */
    public function saveCalendar(string $filename)
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

    /**
     * format content of ICS file
     *
     * @param array $allEvent
     * @return string
     */
    private function transformEventsICS(array $allEvent): string
    {
        $calendar = Calendar::create('Extraction Calendars Events')
                        ->description('Events and meetings with custommers or contacts')
                        ->;
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
}
<?php

namespace Celtic34fr\ContactRendezVous\Twig\Runtime;

use DateTime;

/**
 * TWIG Extension : helpers for parameter up a Fullcalendar object code implementation
 */
class FullcalendarRuntime
{
    /**
     * Get starting date according display mode
     * 
     * @param mixed $date
     * @param mixed $view
     * @return DateTime|string
     */
    public function twigFunction_getStartDate(string $date = "", string $view = "dayGridMonth")
    {
        if (!$date) {
            $date = new DateTime("now");
        } else {
            $date = new DateTime($date);
        }

        switch ($view) {
            case "dayGridMonth":
                $strDate = (new DateTime($date->format("Y-m-") . "01"));
                break;
            case "dayGridWeek":
            case "timeGridWeek":
                $year = (int) $date->format("Y");
                $duedt = explode("-", $date->format("Y-m-d"));
                $day = mktime(0, 0, 0, $duedt[2], $duedt[1], $duedt[0]);
                $week = (int)date('W', $day);
                $timestamp = mktime(0, 0, 0, 1, 1, $year ) + ($week * 7 * 24 * 60 * 60);
                $timestamp_for_monday = $timestamp - 86400 * (date('N', $timestamp) - 1);
                $strDate = date('Y-m-d', $timestamp_for_monday);
                break;
            case "timeGridDay":
            case "dayGridDay":
                $strDate = $date->format("Y-m-d");
                break;
        }
        return $strDate;
    }

    /**
     * Get ending date according display mode
     * 
     * @param mixed $date
     * @param mixed $view
     * @return DateTime|string
     */
    public function twigFunction_getEndDate(string $date = "", string $view = "dayGridMonth")
    {
        if (!$date) {
            $date = new DateTime("now");
        } else {
            $date = new DateTime($date);
        }

        switch ($view) {
            case "dayGridMonth":
                $strDate = (new DateTime($date->modify("+1 month -1 day")->format("Y-m-d")));
                break;
            case "dayGridWeek":
            case "timeGridWeek":
                $year = (int) $date->format("Y");
                $duedt = explode("-", $date->format("Y-m-d"));
                $day = mktime(0, 0, 0, $duedt[2], $duedt[1], $duedt[0]);
                $week = (int)date('W', $day);
                $timestamp = mktime(0, 0, 0, 1, 1, $year) + ($week * 7 * 24 * 60 * 60);
                $timestamp_for_monday = $timestamp - 86400 * (date('N', $timestamp) + 6);
                $strDate = date('Y-m-d', $timestamp_for_monday);
                break;
            case "timeGridDay":
            case "dayGridDay":
                $strDate = $date->format("Y-m-d");
                break;
        }
        return $strDate;
    }
}

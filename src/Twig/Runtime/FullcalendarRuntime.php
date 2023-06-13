<?php

namespace Celtic34fr\ContactRendezVous\Twig\Runtime;

use DateTime;

class FullcalendarRuntime
{
    public function twigFunction_getStartDate(string $date = "", string $view = "dayGridMonth")
    {
        if (!$date) {
            $date = new DateTime("now");
        } else {
            $date = new DateTime($date);
        }

        switch ($view) {
            case "dayGridMonth":
                return (new DateTime($date->format("Y-m-") . "01"));
                break;
            case "dayGridWeek":
            case "timeGridWeek":
                $year = (int) $date->format("Y");
                $duedt = explode("-", $date->format("Y-m-d"));
                $day = mktime(0, 0, 0, $duedt[2], $duedt[1], $duedt[0]);
                $week = (int)date('W', $day);
                $timestamp = mktime(0, 0, 0, 1, 1, $year ) + ($week * 7 * 24 * 60 * 60);
                $timestamp_for_monday = $timestamp - 86400 * (date('N', $timestamp) - 1);
                return date('Y-m-d', $timestamp_for_monday);
                break;
            case "timeGridDay":
            case "dayGridDay":
                return $date->format("Y-m-d");
                break;
        }
    }

    public function twigFunction_getEndDate(string $date = "", string $view = "dayGridMonth")
    {
        if (!$date) {
            $date = new DateTime("now");
        } else {
            $date = new DateTime($date);
        }

        switch ($view) {
            case "dayGridMonth":
                return (new DateTime($date->modify("+1 month -1 day")->format("Y-m-d")));
                break;
            case "dayGridWeek":
            case "timeGridWeek":
                $year = (int) $date->format("Y");
                $duedt = explode("-", $date->format("Y-m-d"));
                $day = mktime(0, 0, 0, $duedt[2], $duedt[1], $duedt[0]);
                $week = (int)date('W', $day);
                $timestamp = mktime(0, 0, 0, 1, 1, $year) + ($week * 7 * 24 * 60 * 60);
                $timestamp_for_monday = $timestamp - 86400 * (date('N', $timestamp) + 6);
                return date('Y-m-d', $timestamp_for_monday);
                break;
            case "timeGridDay":
            case "dayGridDay":
                return $date->format("Y-m-d");
                break;
        }
    }
}

<?php

namespace App\Entity\Enum;

enum CalendarProvider: string
{
    case LOCAL = 'local';
    case GOOGLE = 'google';
    case OUTLOOK = 'outlook';
    case APPLE = 'apple';
    case CALDAV = 'caldav';
    case ICS = 'ics';

    public function getLabel(): string
    {
        return match ($this) {
            self::LOCAL => 'Calendrier interne',
            self::GOOGLE => 'Google Calendar',
            self::OUTLOOK => 'Microsoft Outlook',
            self::APPLE => 'Apple Calendar',
            self::CALDAV => 'CalDAV',
            self::ICS => 'Fichier iCalendar (.ics)',
        };
    }

    public function getIcon(): string
    {
        return match ($this) {
            self::LOCAL => 'fa-solid fa-calendar',
            self::GOOGLE => 'fa-brands fa-google',
            self::OUTLOOK => 'fa-solid fa-envelope',
            self::APPLE => 'fa-brands fa-apple',
            self::CALDAV => 'fa-solid fa-cloud',
            self::ICS => 'fa-solid fa-file-import',
        };
    }
    public static function choices(): array
    {
        $choices = [];

        foreach (self::cases() as $case) {
            $choices[$case->getLabel()] = $case;
        }

        return $choices;
    }
}

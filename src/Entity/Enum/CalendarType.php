<?php

namespace App\Enum;

enum CalendarType: string
{
    case PERSONAL = 'personal';
    case ORGANIZATION = 'organization';
    case TEAM = 'team';
    case RESOURCE = 'resource';
    case HOLIDAY = 'holiday';
    case ABSENCE = 'absence';
    case SHARED = 'shared';

    public function getLabel(): string
    {
        return match ($this) {
            self::PERSONAL => 'Personnel',
            self::ORGANIZATION => 'Organisation',
            self::TEAM => 'Équipe',
            self::RESOURCE => 'Ressource',
            self::HOLIDAY => 'Jours fériés',
            self::ABSENCE => 'Absences',
            self::SHARED => 'Partagé',
        };
    }

    public function getIcon(): string
    {
        return match ($this) {
            self::PERSONAL => 'fa-solid fa-user',
            self::ORGANIZATION => 'fa-solid fa-building',
            self::TEAM => 'fa-solid fa-users',
            self::RESOURCE => 'fa-solid fa-toolbox',
            self::HOLIDAY => 'fa-solid fa-calendar-day',
            self::ABSENCE => 'fa-solid fa-bed',
            self::SHARED => 'fa-solid fa-share-nodes',
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

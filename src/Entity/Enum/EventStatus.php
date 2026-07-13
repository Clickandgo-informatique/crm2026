<?php

namespace App\Entity\Enum;

enum EventStatus: string
{
    case PLANNED = 'planned';
    case CONFIRMED = 'confirmed';
    case DONE = 'done';
    case CANCELLED = 'cancelled';

    public function label(): string
    {
        return match ($this) {
            self::PLANNED => 'Planifié',
            self::CONFIRMED => 'Confirmé',
            self::DONE => 'Terminé',
            self::CANCELLED => 'Annulé',
        };
    }

    public function icon(): string
    {
        return match ($this) {
            self::PLANNED => 'calendar-clock',
            self::CONFIRMED => 'calendar-check',
            self::DONE => 'circle-check',
            self::CANCELLED => 'circle-x',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::PLANNED => 'blue',
            self::CONFIRMED => 'green',
            self::DONE => 'gray',
            self::CANCELLED => 'red',
        };
    }
}

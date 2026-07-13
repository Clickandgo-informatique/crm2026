<?php

namespace App\Entity\Enum;

enum EventType: string
{
    case APPOINTMENT = 'appointment';
    case TASK = 'task';
    case CALL = 'call';
    case DELIVERY = 'delivery';
    case INTERVENTION = 'intervention';

    public function label(): string
    {
        return match ($this) {
            self::APPOINTMENT => 'Rendez-vous',
            self::TASK => 'Tâche',
            self::CALL => 'Appel',
            self::DELIVERY => 'Livraison',
            self::INTERVENTION => 'Intervention',
        };
    }

    public function icon(): string
    {
        return match ($this) {
            self::APPOINTMENT => 'fa-calendar-days',
            self::TASK => 'fa-clipboard-check',
            self::CALL => 'fa-phone',
            self::DELIVERY => 'fa-truck',
            self::INTERVENTION => 'fa-screwdriver-wrench',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::APPOINTMENT => 'blue',
            self::TASK => 'purple',
            self::CALL => 'green',
            self::DELIVERY => 'orange',
            self::INTERVENTION => 'red',
        };
    }
}

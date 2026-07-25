<?php

namespace App\Entity\Enum;

enum CalendarSyncStatus: string
{
    case ACTIVE = 'active';
    case PENDING = 'pending';
    case ERROR = 'error';
    case DISABLED = 'disabled';

    public function getLabel(): string
    {
        return match ($this) {
            self::ACTIVE => 'Synchronisé',
            self::PENDING => 'En attente',
            self::ERROR => 'Erreur',
            self::DISABLED => 'Désactivé',
        };
    }

    public function getIcon(): string
    {
        return match ($this) {
            self::ACTIVE => 'fa-solid fa-circle-check',
            self::PENDING => 'fa-solid fa-clock',
            self::ERROR => 'fa-solid fa-circle-exclamation',
            self::DISABLED => 'fa-solid fa-circle-xmark',
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

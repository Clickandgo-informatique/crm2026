<?php

namespace App\Entity\Enum;

enum DossierStatus: string
{
    case DRAFT = 'draft';
    case OPEN = 'open';
    case IN_PROGRESS = 'in_progress';
    case WAITING = 'waiting';
    case COMPLETED = 'completed';
    case CANCELLED = 'cancelled';


    public function label(): string
    {
        return match ($this) {
            self::DRAFT => 'Brouillon',
            self::OPEN => 'Ouvert',
            self::IN_PROGRESS => 'En cours',
            self::WAITING => 'En attente',
            self::COMPLETED => 'Terminé',
            self::CANCELLED => 'Annulé',
        };
    }


    public function icon(): string
    {
        return match ($this) {
            self::DRAFT => 'fa-solid fa-file',
            self::OPEN => 'fa-solid fa-folder-open',
            self::IN_PROGRESS => 'fa-solid fa-spinner',
            self::WAITING => 'fa-solid fa-clock',
            self::COMPLETED => 'fa-solid fa-circle-check',
            self::CANCELLED => 'fa-solid fa-circle-xmark',
        };
    }


    public function color(): string
    {
        return match ($this) {
            self::DRAFT => 'secondary',
            self::OPEN => 'primary',
            self::IN_PROGRESS => 'warning',
            self::WAITING => 'info',
            self::COMPLETED => 'success',
            self::CANCELLED => 'danger',
        };
    }


    public static function choices(): array
    {
        $choices = [];

        foreach (self::cases() as $status) {
            $choices[$status->label()] = $status;
        }

        return $choices;
    }
}

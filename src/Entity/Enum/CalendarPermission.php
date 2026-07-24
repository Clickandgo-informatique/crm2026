<?php

namespace App\Enum;

enum CalendarPermission: string
{
    case OWNER = 'owner';
    case EDITOR = 'editor';
    case CONTRIBUTOR = 'contributor';
    case READER = 'reader';

    public function getLabel(): string
    {
        return match ($this) {
            self::OWNER => 'Propriétaire',
            self::EDITOR => 'Éditeur',
            self::CONTRIBUTOR => 'Contributeur',
            self::READER => 'Lecteur',
        };
    }

    public function getIcon(): string
    {
        return match ($this) {
            self::OWNER => 'fa-solid fa-crown',
            self::EDITOR => 'fa-solid fa-pen',
            self::CONTRIBUTOR => 'fa-solid fa-user-pen',
            self::READER => 'fa-solid fa-eye',
        };
    }
}
<?php

namespace App\Service\DateTime\DTO;

class DateTimePreferences
{
    public function __construct(
        public readonly string $timezone = 'Europe/Paris',
        public readonly string $locale = 'fr_FR',
        public readonly string $dateFormat = 'dd/MM/yyyy',
        public readonly string $timeFormat = 'HH:mm',
        public readonly int $firstDayOfWeek = 1
    ) {
    }
}
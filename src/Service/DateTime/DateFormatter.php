<?php

namespace App\Service\DateTime;

use App\Entity\UserPreference;
use DateTimeImmutable;
use IntlDateFormatter;

class DateFormatter
{
    public function __construct(
        private readonly TimeZoneService $timeZoneService
    ) {}

    /**
     * Formate une date selon les préférences utilisateur.
     */
    public function formatDate(
        DateTimeImmutable $date,
        ?UserPreference $preference = null
    ): string {
        return $this->format(
            $date,
            $preference,
            IntlDateFormatter::SHORT,
            IntlDateFormatter::NONE
        );
    }

    /**
     * Formate une heure selon les préférences utilisateur.
     */
    public function formatTime(
        DateTimeImmutable $date,
        ?UserPreference $preference = null
    ): string {
        return $this->format(
            $date,
            $preference,
            IntlDateFormatter::NONE,
            IntlDateFormatter::SHORT
        );
    }

    /**
     * Formate une date et une heure selon les préférences utilisateur.
     */
    public function formatDateTime(
        DateTimeImmutable $date,
        ?UserPreference $preference = null
    ): string {
        return $this->format(
            $date,
            $preference,
            IntlDateFormatter::SHORT,
            IntlDateFormatter::SHORT
        );
    }

    /**
     * Formate une date avec les paramètres régionaux utilisateur.
     */
    private function format(
        DateTimeImmutable $date,
        ?UserPreference $preference,
        int $dateType,
        int $timeType
    ): string {
        $locale = $preference?->getLocale() ?? 'fr_FR';
        $timezone = $this->timeZoneService->getUserTimeZone($preference);

        $formatter = new IntlDateFormatter(
            $locale,
            $dateType,
            $timeType,
            $timezone->getName()
        );

        return $formatter->format($date);
    }
}

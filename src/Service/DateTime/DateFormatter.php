<?php

namespace App\Service\DateTime;

use App\Service\DateTime\DTO\DateTimePreferences;
use DateTimeImmutable;
use IntlDateFormatter;
use Locale;

class DateFormatter
{
    public function __construct(
        private readonly DateTimePreferencesService $preferencesService
    ) {}

    /**
     * Formate une date courte.
     */
    public function formatDate(DateTimeImmutable $date): string
    {
        return $this->format(
            $date,
            $this->getPreferences()->dateFormat
        );
    }

    /**
     * Formate une heure.
     */
    public function formatTime(DateTimeImmutable $date): string
    {
        return $this->format(
            $date,
            $this->getPreferences()->timeFormat
        );
    }

    /**
     * Formate une date avec heure.
     */
    public function formatDateTime(DateTimeImmutable $date): string
    {
        return $this->format(
            $date,
            $this->getPreferences()->dateFormat . ' ' .
                $this->getPreferences()->timeFormat
        );
    }

    /**
     * Formate une date longue.
     */
    public function formatLongDate(DateTimeImmutable $date): string
    {
        $formatter = new IntlDateFormatter(
            $this->getPreferences()->locale,
            IntlDateFormatter::FULL,
            IntlDateFormatter::NONE,
            $date->getTimezone(),
            IntlDateFormatter::GREGORIAN
        );

        return $formatter->format($date);
    }

    /**
     * Retourne une date relative.
     */
    public function formatRelative(
        DateTimeImmutable $date,
        ?DateTimeImmutable $reference = null
    ): string {
        $reference ??= new DateTimeImmutable();

        $diff = $reference->diff($date);

        if ($diff->days === 0) {
            return 'Aujourd’hui';
        }

        if ($diff->days === 1 && $diff->invert === 0) {
            return 'Demain';
        }

        if ($diff->days === 1 && $diff->invert === 1) {
            return 'Hier';
        }

        return $this->formatDate($date);
    }

    /**
     * Effectue le formatage avec Intl.
     */
    private function format(
        DateTimeImmutable $date,
        string $pattern
    ): string {
        $formatter = new IntlDateFormatter(
            $this->getPreferences()->locale,
            IntlDateFormatter::NONE,
            IntlDateFormatter::NONE,
            $date->getTimezone(),
            IntlDateFormatter::GREGORIAN,
            $pattern
        );

        return $formatter->format($date);
    }

    /**
     * Retourne les préférences actuelles.
     */
    private function getPreferences(): DateTimePreferences
    {
        return $this->preferencesService->getPreferences();
    }
}

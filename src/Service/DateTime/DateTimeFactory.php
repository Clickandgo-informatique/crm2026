<?php

namespace App\Service\DateTime;

use App\Service\DateTime\DTO\TimeZoneService;
use DateTimeImmutable;
use DateTimeZone;

class DateTimeFactory
{
    public function __construct(
        private readonly TimeZoneService $timeZoneService,
        private readonly DateTimePreferencesService $preferencesService
    ) {}

    /**
     * Retourne la date et l'heure courantes.
     */
    public function now(?string $timeZone = null): DateTimeImmutable
    {
        return new DateTimeImmutable(
            'now',
            $this->getTimeZone($timeZone)
        );
    }

    /**
     * Retourne la date du jour à minuit.
     */
    public function today(?string $timeZone = null): DateTimeImmutable
    {
        return $this->now($timeZone)->setTime(0, 0);
    }

    /**
     * Retourne la date de demain à minuit.
     */
    public function tomorrow(?string $timeZone = null): DateTimeImmutable
    {
        return $this->today($timeZone)->modify('+1 day');
    }

    /**
     * Retourne la date d'hier à minuit.
     */
    public function yesterday(?string $timeZone = null): DateTimeImmutable
    {
        return $this->today($timeZone)->modify('-1 day');
    }

    /**
     * Crée une date à partir d'une chaîne.
     */
    public function create(string $dateTime, ?string $timeZone = null): DateTimeImmutable
    {
        return new DateTimeImmutable(
            $dateTime,
            $this->getTimeZone($timeZone)
        );
    }

    /**
     * Crée une date à partir d'un timestamp Unix.
     */
    public function fromTimestamp(int $timestamp): DateTimeImmutable
    {
        return (new DateTimeImmutable())->setTimestamp($timestamp);
    }

    /**
     * Retourne une date avec une heure précise.
     */
    public function at(
        DateTimeImmutable $date,
        int $hour,
        int $minute = 0,
        int $second = 0
    ): DateTimeImmutable {
        return $date->setTime($hour, $minute, $second);
    }

    /**
     * Retourne une date sans la partie heure.
     */
    public function date(DateTimeImmutable $date): DateTimeImmutable
    {
        return $date->setTime(0, 0, 0);
    }

    /**
     * Retourne le fuseau horaire demandé ou celui des préférences utilisateur.
     */
    private function getTimeZone(?string $timeZone): DateTimeZone
    {
        $identifier = $timeZone
            ?? $this->preferencesService->getPreferences()->timezone;

        return $this->timeZoneService->getTimeZone($identifier);
    }
}

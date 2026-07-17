<?php

namespace App\Service\DateTime;


use App\Service\DateTime\DTO\TimeZoneOption;
use DateTimeImmutable;
use DateTimeZone;
use InvalidArgumentException;

class TimeZoneService
{
    // Cache des fuseaux horaires construits pendant la requête.
    private array $options = [];

    /**
     * Retourne tous les identifiants IANA.
     *
     * @return string[]
     */
    public function getAllTimeZones(): array
    {
        return DateTimeZone::listIdentifiers();
    }

    /**
     * Vérifie qu'un identifiant de fuseau horaire est valide.
     */
    public function isValid(string $identifier): bool
    {
        return in_array($identifier, DateTimeZone::listIdentifiers(), true);
    }

    /**
     * Retourne un objet DateTimeZone correspondant à l'identifiant.
     *
     * @throws InvalidArgumentException
     */
    public function getTimeZone(string $identifier): DateTimeZone
    {
        if (!$this->isValid($identifier)) {
            throw new InvalidArgumentException(sprintf('Unknown timezone "%s".', $identifier));
        }

        return new DateTimeZone($identifier);
    }

    /**
     * Convertit une date d'un fuseau horaire vers un autre.
     */
    public function convert(DateTimeImmutable $date, string $from, string $to): DateTimeImmutable
    {
        $date = $date->setTimezone($this->getTimeZone($from));

        return $date->setTimezone($this->getTimeZone($to));
    }

    /**
     * Retourne le décalage UTC en secondes.
     */
    public function getOffset(string $identifier, ?DateTimeImmutable $date = null): int
    {
        $date ??= new DateTimeImmutable();

        return $this->getTimeZone($identifier)->getOffset($date);
    }

    /**
     * Retourne le fuseau horaire par défaut de PHP.
     */
    public function getDefaultTimeZone(): string
    {
        return date_default_timezone_get();
    }

    /**
     * Retourne les fuseaux horaires sous forme de choix pour un formulaire Symfony.
     *
     * @return array<string, array<string, string>>
     */
    public function getChoices(): array
    {
        $choices = [];

        foreach ($this->getGroupedOptions() as $continent => $options) {
            foreach ($options as $option) {
                $choices[$continent][$option->label] = $option->identifier;
            }
        }

        return $choices;
    }

    /**
     * Retourne tous les fuseaux horaires sous forme d'objets.
     *
     * @return TimeZoneOption[]
     */
    public function getOptions(): array
    {
        if ($this->options !== []) {
            return $this->options;
        }

        foreach (DateTimeZone::listIdentifiers() as $identifier) {
            $this->options[] = $this->buildOption($identifier);
        }

        return $this->options;
    }

    /**
     * Retourne les fuseaux horaires regroupés par continent.
     *
     * @return array<string, TimeZoneOption[]>
     */
    public function getGroupedOptions(): array
    {
        $result = [];

        foreach ($this->getOptions() as $option) {
            $result[$option->continent][] = $option;
        }

        ksort($result);

        foreach ($result as &$group) {
            usort(
                $group,
                fn(TimeZoneOption $a, TimeZoneOption $b) => strcmp($a->city, $b->city)
            );
        }

        return $result;
    }

    /**
     * Construit un objet représentant un fuseau horaire.
     */
    private function buildOption(string $identifier): TimeZoneOption
    {
        $timezone = $this->getTimeZone($identifier);

        $parts = explode('/', $identifier, 2);

        $continent = $parts[0];
        $city = str_replace('_', ' ', $parts[1] ?? $identifier);

        $offset = $timezone->getOffset(new DateTimeImmutable());

        $label = sprintf(
            '(%s) %s',
            $this->formatOffset($offset),
            $city
        );

        return new TimeZoneOption(
            identifier: $identifier,
            continent: $continent,
            city: $city,
            label: $label,
            offset: $offset
        );
    }

    /**
     * Formate un décalage UTC.
     */
    private function formatOffset(int $offset): string
    {
        $sign = $offset >= 0 ? '+' : '-';
        $offset = abs($offset);

        return sprintf(
            'UTC%s%02d:%02d',
            $sign,
            intdiv($offset, 3600),
            intdiv($offset % 3600, 60)
        );
    }
}

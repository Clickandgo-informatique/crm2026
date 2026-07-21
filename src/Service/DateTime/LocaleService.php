<?php

namespace App\Service\DateTime;

class LocaleService
{
    private const LOCALES = [
        'Français' => 'fr_FR',
        'English' => 'en_GB',
        'Deutsch' => 'de_DE',
        'Español' => 'es_ES',
        'Italiano' => 'it_IT',
    ];

    public function getChoices(): array
    {
        return self::LOCALES;
    }

    public function isSupported(string $locale): bool
    {
        return in_array($locale, self::LOCALES, true);
    }

    public function getDefault(): string
    {
        return 'fr_FR';
    }
}
<?php

namespace App\Service\DateTime;

use App\Entity\User;
use App\Service\DateTime\DTO\DateTimePreferences;
use Symfony\Bundle\SecurityBundle\Security;

class DateTimePreferencesService
{
    public function __construct(
        private readonly Security $security
    ) {}

    /**
     * Retourne les préférences de l'utilisateur courant.
     */
    public function getPreferences(): DateTimePreferences
    {
        $user = $this->security->getUser();

        if ($user instanceof User && $user->getPreference() !== null) {
            $preference = $user->getPreference();

            return new DateTimePreferences(
                timezone: $preference->getTimezone(),
                locale: $preference->getLocale(),
                dateFormat: $preference->getDateFormat(),
                timeFormat: $preference->getTimeFormat(),
                firstDayOfWeek: $preference->getFirstDayOfWeek()
            );
        }

        return new DateTimePreferences();
    }
}

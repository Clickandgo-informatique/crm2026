<?php

namespace App\DataFixtures;

use App\Entity\CalendarEvent;
use App\Entity\Enum\EventType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class CalendarEventFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        $types = [
            EventType::APPOINTMENT,
            EventType::TASK,
            EventType::CALL,
            EventType::DELIVERY,
            EventType::INTERVENTION,
        ];

        $titles = [
            'appointment' => [
                'Rendez-vous client',
                'Réunion de suivi',
                'Présentation commerciale',
                'Point projet',
            ],
            'task' => [
                'Préparation dossier',
                'Vérification documents',
                'Mise à jour informations',
                'Traitement administratif',
            ],
            'call' => [
                'Appel client',
                'Relance téléphonique',
                'Point téléphonique',
            ],
            'delivery' => [
                'Livraison matériel',
                'Installation équipement',
                'Réception commande',
            ],
            'intervention' => [
                'Intervention technique',
                'Maintenance',
                'Diagnostic',
                'Dépannage',
            ],
        ];

        $startDate = new \DateTimeImmutable('-3 years');
        $endDate = new \DateTimeImmutable('+3 years');

        for ($i = 0; $i < 4000; $i++) {

            $type = $faker->randomElement($types);

            $startAt = \DateTimeImmutable::createFromMutable(
                $faker->dateTimeBetween(
                    $startDate->format('Y-m-d'),
                    $endDate->format('Y-m-d')
                )
            );

            $isMultiDay = $faker->boolean(8);
            $isAllDay = $faker->boolean(15);

            if ($isMultiDay) {

                $durationDays = $faker->numberBetween(2, 5);

                $endAt = $startAt->modify(
                    sprintf('+%d days', $durationDays)
                );
            } elseif ($isAllDay) {

                $endAt = $startAt->modify('+1 day');
            } else {

                $hour = $faker->numberBetween(8, 17);

                $startAt = $startAt
                    ->setTime(
                        $hour,
                        $faker->randomElement([0, 15, 30, 45])
                    );

                $durationMinutes = $faker->randomElement([
                    30,
                    45,
                    60,
                    90,
                    120,
                ]);

                $endAt = $startAt->modify(
                    "+{$durationMinutes} minutes"
                );
            }

            $event = new CalendarEvent();

            $event->setTitle(
                $faker->randomElement(
                    $titles[$type->value]
                )
            );

            $event->setDescription(
                $faker->optional(0.6)->paragraph()
            );

            $event->setStartAt(
                $startAt
            );

            $event->setEndAt(
                $endAt
            );

            $event->setAllDay(
                $isAllDay
            );

            $event->setType(
                $type
            );

            $manager->persist($event);
        }

        $manager->flush();
    }
}

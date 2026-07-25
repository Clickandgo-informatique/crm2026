<?php

namespace App\DataFixtures;

use App\Entity\Calendar;
use App\Entity\CalendarEvent;
use App\Entity\Enum\EventType;
use App\Entity\StaffMember;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class CalendarEventFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        $calendarResources = [
            CalendarFixtures::CALENDAR_GARAGE => [
                StaffMemberFixtures::GARAGE_MANAGER,
                StaffMemberFixtures::GARAGE_TECHNICIAN,
                StaffMemberFixtures::GARAGE_ASSISTANT,
            ],
            CalendarFixtures::CALENDAR_SCHOOL => [
                StaffMemberFixtures::SCHOOL_DIRECTOR,
                StaffMemberFixtures::SCHOOL_SECRETARY,
                StaffMemberFixtures::SCHOOL_TEACHER,
            ],
            CalendarFixtures::CALENDAR_MEDICAL => [
                StaffMemberFixtures::MEDICAL_DOCTOR,
                StaffMemberFixtures::MEDICAL_ASSISTANT,
                StaffMemberFixtures::MEDICAL_SECRETARY,
            ],
            CalendarFixtures::CALENDAR_CONCIERGE => [
                StaffMemberFixtures::CONCIERGE_MANAGER,
                StaffMemberFixtures::CONCIERGE_AGENT,
                StaffMemberFixtures::CONCIERGE_TECHNICIAN,
            ],
        ];

        $calendars = [];

        foreach ($calendarResources as $calendarReference => $staffReferences) {
            /** @var Calendar $calendar */
            $calendar = $this->getReference(
                $calendarReference,
                Calendar::class
            );

            $resources = [];

            foreach ($staffReferences as $staffReference) {
                $resources[] = $this->getReference(
                    $staffReference,
                    StaffMember::class
                );
            }

            $calendars[] = [
                'calendar' => $calendar,
                'resources' => $resources,
            ];
        }

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

        $startDate = new \DateTimeImmutable('-1 year');
        $endDate = new \DateTimeImmutable('+1 year');

        for ($i = 0; $i < 400; $i++) {
            $type = $faker->randomElement($types);

            $calendarData = $faker->randomElement($calendars);

            $startAt = \DateTimeImmutable::createFromMutable(
                $faker->dateTimeBetween(
                    $startDate->format('Y-m-d'),
                    $endDate->format('Y-m-d')
                )
            );

            $isMultiDay = $faker->boolean(8);
            $isAllDay = $faker->boolean(15);

            if ($isMultiDay) {
                $endAt = $startAt->modify(
                    sprintf(
                        '+%d days',
                        $faker->numberBetween(2, 5)
                    )
                );
            } elseif ($isAllDay) {
                $endAt = $startAt->modify('+1 day');
            } else {
                $startAt = $startAt->setTime(
                    $faker->numberBetween(8, 17),
                    $faker->randomElement([
                        0,
                        15,
                        30,
                        45,
                    ])
                );

                $endAt = $startAt->modify(
                    '+' . $faker->randomElement([
                        30,
                        45,
                        60,
                        90,
                        120,
                    ]) . ' minutes'
                );
            }

            $event = new CalendarEvent();

            $event
                ->setCalendar(
                    $calendarData['calendar']
                )
                ->setStaffMember(
                    $faker->randomElement(
                        $calendarData['resources']
                    )
                )
                ->setTitle(
                    $faker->randomElement(
                        $titles[$type->value]
                    )
                )
                ->setDescription(
                    $faker->optional(0.6)->paragraph()
                )
                ->setStartAt($startAt)
                ->setEndAt($endAt)
                ->setAllDay($isAllDay)
                ->setType($type);

            $manager->persist($event);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            CalendarFixtures::class,
            StaffMemberFixtures::class,
        ];
    }
}

<?php

namespace App\DataFixtures;

use App\Entity\Calendar;
use App\Entity\Tenant;
use App\Entity\User;
use App\Entity\Enum\CalendarType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class CalendarFixtures extends Fixture implements DependentFixtureInterface
{
    public const CALENDAR_PERSONAL_ADMIN = 'calendar.personal.admin';
    public const CALENDAR_GARAGE = 'calendar.garage';
    public const CALENDAR_SCHOOL = 'calendar.school';
    public const CALENDAR_MEDICAL = 'calendar.medical';
    public const CALENDAR_CONCIERGE = 'calendar.concierge';

    public function load(ObjectManager $manager): void
    {
        /** @var User $owner */
        $owner = $this->getReference(
            UserFixtures::ADMIN,
            User::class
        );

        $calendars = [
            [
                'reference' => self::CALENDAR_PERSONAL_ADMIN,
                'tenant' => TenantFixtures::TENANT_1,
                'name' => 'Calendrier personnel',
                'description' => 'Calendrier personnel de l’administrateur.',
                'type' => CalendarType::PERSONAL,
                'color' => '#3788D8',
                'owner' => $owner,
                'default' => true,
            ],
            [
                'reference' => self::CALENDAR_GARAGE,
                'tenant' => TenantFixtures::TENANT_1,
                'name' => 'Planning Garage Dupont',
                'description' => 'Planning des interventions du garage.',
                'type' => CalendarType::RESOURCE,
                'color' => '#F97316',
                'owner' => null,
                'default' => false,
            ],
            [
                'reference' => self::CALENDAR_SCHOOL,
                'tenant' => TenantFixtures::TENANT_2,
                'name' => 'Planning école Le Lys',
                'description' => 'Planning des équipes scolaires.',
                'type' => CalendarType::RESOURCE,
                'color' => '#10B981',
                'owner' => null,
                'default' => false,
            ],
            [
                'reference' => self::CALENDAR_MEDICAL,
                'tenant' => TenantFixtures::TENANT_3,
                'name' => 'Planning Medic-Aide',
                'description' => 'Planning des professionnels de santé.',
                'type' => CalendarType::RESOURCE,
                'color' => '#8B5CF6',
                'owner' => null,
                'default' => false,
            ],
            [
                'reference' => self::CALENDAR_CONCIERGE,
                'tenant' => TenantFixtures::TENANT_4,
                'name' => 'Planning Ultima',
                'description' => 'Planning des agents terrain.',
                'type' => CalendarType::RESOURCE,
                'color' => '#EF4444',
                'owner' => null,
                'default' => false,
            ],
        ];

        foreach ($calendars as $data) {
            /** @var Tenant $tenant */
            $tenant = $this->getReference(
                $data['tenant'],
                Tenant::class
            );

            $calendar = new Calendar();

            $calendar
                ->setTenant($tenant)
                ->setOwner($data['owner'])
                ->setName($data['name'])
                ->setDescription($data['description'])
                ->setType($data['type'])
                ->setColor($data['color'])
                ->setTimezone('Europe/Paris')
                ->setVisible(true)
                ->setDefault($data['default'])
                ->setReadOnly(false);

            $manager->persist($calendar);

            $this->addReference(
                $data['reference'],
                $calendar
            );
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            TenantFixtures::class,
            UserFixtures::class,
        ];
    }
}

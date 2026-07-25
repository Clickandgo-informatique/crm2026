<?php

namespace App\DataFixtures;

use App\Entity\StaffMember;
use App\Entity\Tenant;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class StaffMemberFixtures extends Fixture implements DependentFixtureInterface
{
    public const GARAGE_TECHNICIAN = 'staff.garage.technician';
    public const GARAGE_ASSISTANT = 'staff.garage.assistant';
    public const GARAGE_MANAGER = 'staff.garage.manager';

    public const SCHOOL_DIRECTOR = 'staff.school.director';
    public const SCHOOL_SECRETARY = 'staff.school.secretary';
    public const SCHOOL_TEACHER = 'staff.school.teacher';

    public const MEDICAL_DOCTOR = 'staff.medical.doctor';
    public const MEDICAL_ASSISTANT = 'staff.medical.assistant';
    public const MEDICAL_SECRETARY = 'staff.medical.secretary';

    public const CONCIERGE_MANAGER = 'staff.concierge.manager';
    public const CONCIERGE_AGENT = 'staff.concierge.agent';
    public const CONCIERGE_TECHNICIAN = 'staff.concierge.technician';

    public function load(ObjectManager $manager): void
    {
        $staffMembers = [
            [
                self::GARAGE_TECHNICIAN,
                TenantFixtures::TENANT_1,
                'Pierre',
                'Dupont',
                'pierre.dupont@garage.fr',
            ],
            [
                self::GARAGE_ASSISTANT,
                TenantFixtures::TENANT_1,
                'Julie',
                'Martin',
                'julie.martin@garage.fr',
            ],
            [
                self::GARAGE_MANAGER,
                TenantFixtures::TENANT_1,
                'Marc',
                'Durand',
                'marc.durand@garage.fr',
            ],

            [
                self::SCHOOL_DIRECTOR,
                TenantFixtures::TENANT_2,
                'Claire',
                'Bernard',
                'claire.bernard@lelys.fr',
            ],
            [
                self::SCHOOL_SECRETARY,
                TenantFixtures::TENANT_2,
                'Sophie',
                'Robert',
                'sophie.robert@lelys.fr',
            ],
            [
                self::SCHOOL_TEACHER,
                TenantFixtures::TENANT_2,
                'Thomas',
                'Petit',
                'thomas.petit@lelys.fr',
            ],

            [
                self::MEDICAL_DOCTOR,
                TenantFixtures::TENANT_3,
                'Antoine',
                'Leroy',
                'antoine.leroy@medicaide.fr',
            ],
            [
                self::MEDICAL_ASSISTANT,
                TenantFixtures::TENANT_3,
                'Emma',
                'Moreau',
                'emma.moreau@medicaide.fr',
            ],
            [
                self::MEDICAL_SECRETARY,
                TenantFixtures::TENANT_3,
                'Laura',
                'Simon',
                'laura.simon@medicaide.fr',
            ],

            [
                self::CONCIERGE_MANAGER,
                TenantFixtures::TENANT_4,
                'Nicolas',
                'Garcia',
                'nicolas.garcia@ultima.fr',
            ],
            [
                self::CONCIERGE_AGENT,
                TenantFixtures::TENANT_4,
                'Julie',
                'Roux',
                'julie.roux@ultima.fr',
            ],
            [
                self::CONCIERGE_TECHNICIAN,
                TenantFixtures::TENANT_4,
                'Alexandre',
                'Fontaine',
                'alexandre.fontaine@ultima.fr',
            ],
        ];

        foreach ($staffMembers as [$reference, $tenantReference, $firstName, $lastName, $email]) {
            /** @var Tenant $tenant */
            $tenant = $this->getReference(
                $tenantReference,
                Tenant::class
            );

            $staffMember = new StaffMember();

            $staffMember
                ->setTenant($tenant)
                ->setFirstName($firstName)
                ->setLastName($lastName)
                ->setEmail($email)
                ->setActive(true);

            $manager->persist($staffMember);

            $this->addReference($reference, $staffMember);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            TenantFixtures::class,
        ];
    }
}
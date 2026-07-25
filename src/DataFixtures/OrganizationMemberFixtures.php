<?php

namespace App\DataFixtures;

use App\Entity\Organization;
use App\Entity\OrganizationMember;
use App\Entity\StaffMember;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class OrganizationMemberFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $members = [
            [
                OrganizationFixtures::ORGANIZATION_1,
                StaffMemberFixtures::GARAGE_MANAGER,
                'Responsable atelier',
                55,
            ],
            [
                OrganizationFixtures::ORGANIZATION_1,
                StaffMemberFixtures::GARAGE_TECHNICIAN,
                'Technicien',
                45,
            ],
            [
                OrganizationFixtures::ORGANIZATION_1,
                StaffMemberFixtures::GARAGE_ASSISTANT,
                'Assistant administratif',
                30,
            ],

            [
                OrganizationFixtures::ORGANIZATION_4,
                StaffMemberFixtures::SCHOOL_DIRECTOR,
                'Directeur',
                null,
            ],
            [
                OrganizationFixtures::ORGANIZATION_4,
                StaffMemberFixtures::SCHOOL_SECRETARY,
                'Secrétaire',
                null,
            ],
            [
                OrganizationFixtures::ORGANIZATION_4,
                StaffMemberFixtures::SCHOOL_TEACHER,
                'Enseignant',
                null,
            ],

            [
                OrganizationFixtures::ORGANIZATION_7,
                StaffMemberFixtures::MEDICAL_DOCTOR,
                'Médecin',
                80,
            ],
            [
                OrganizationFixtures::ORGANIZATION_7,
                StaffMemberFixtures::MEDICAL_ASSISTANT,
                'Assistant médical',
                35,
            ],
            [
                OrganizationFixtures::ORGANIZATION_7,
                StaffMemberFixtures::MEDICAL_SECRETARY,
                'Secrétaire médicale',
                30,
            ],

            [
                OrganizationFixtures::ORGANIZATION_10,
                StaffMemberFixtures::CONCIERGE_MANAGER,
                'Responsable secteur',
                50,
            ],
            [
                OrganizationFixtures::ORGANIZATION_10,
                StaffMemberFixtures::CONCIERGE_AGENT,
                'Agent de terrain',
                35,
            ],
            [
                OrganizationFixtures::ORGANIZATION_10,
                StaffMemberFixtures::CONCIERGE_TECHNICIAN,
                'Technicien maintenance',
                45,
            ],
        ];

        foreach ($members as [$organizationReference, $staffReference, $role, $hourlyRate]) {
            /** @var Organization $organization */
            $organization = $this->getReference(
                $organizationReference,
                Organization::class
            );

            /** @var StaffMember $staffMember */
            $staffMember = $this->getReference(
                $staffReference,
                StaffMember::class
            );

            $member = new OrganizationMember();

            $member
                ->setOrganization($organization)
                ->setStaffMember($staffMember)
                ->setRole($role)
                ->setHourlyRate($hourlyRate)
                ->setActive(true)
                ->setStartDate(new \DateTimeImmutable('2026-01-01'));

            $manager->persist($member);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            OrganizationFixtures::class,
            StaffMemberFixtures::class,
        ];
    }
}

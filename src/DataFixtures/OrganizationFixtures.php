<?php

namespace App\DataFixtures;

use App\Entity\Organization;
use App\Entity\Tenant;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class OrganizationFixtures extends Fixture implements DependentFixtureInterface
{
    public const ORGANIZATION_1 = 'organization_1';
    public const ORGANIZATION_2 = 'organization_2';
    public const ORGANIZATION_3 = 'organization_3';
    public const ORGANIZATION_4 = 'organization_4';
    public const ORGANIZATION_5 = 'organization_5';
    public const ORGANIZATION_6 = 'organization_6';
    public const ORGANIZATION_7 = 'organization_7';
    public const ORGANIZATION_8 = 'organization_8';
    public const ORGANIZATION_9 = 'organization_9';
    public const ORGANIZATION_10 = 'organization_10';
    public const ORGANIZATION_11 = 'organization_11';
    public const ORGANIZATION_12 = 'organization_12';

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        $organizations = [
            [TenantFixtures::TENANT_1, self::ORGANIZATION_1, 'Garage Dupont'],
            [TenantFixtures::TENANT_1, self::ORGANIZATION_2, 'Garage Dupont - Atelier'],
            [TenantFixtures::TENANT_1, self::ORGANIZATION_3, 'Garage Dupont - Carrosserie'],

            [TenantFixtures::TENANT_2, self::ORGANIZATION_4, 'École primaire Le Lys'],
            [TenantFixtures::TENANT_2, self::ORGANIZATION_5, 'Cantine scolaire'],
            [TenantFixtures::TENANT_2, self::ORGANIZATION_6, 'Accueil périscolaire'],

            [TenantFixtures::TENANT_3, self::ORGANIZATION_7, 'Cabinet Medic-Aide'],
            [TenantFixtures::TENANT_3, self::ORGANIZATION_8, 'Centre de radiologie'],
            [TenantFixtures::TENANT_3, self::ORGANIZATION_9, 'Laboratoire d’analyses'],

            [TenantFixtures::TENANT_4, self::ORGANIZATION_10, 'Ultima Paris'],
            [TenantFixtures::TENANT_4, self::ORGANIZATION_11, 'Ultima Lyon'],
            [TenantFixtures::TENANT_4, self::ORGANIZATION_12, 'Ultima Marseille'],
        ];

        foreach ($organizations as [$tenantReference, $reference, $name]) {
            $organization = new Organization();
            $organization
                ->setTenant($this->getReference($tenantReference, Tenant::class))
                ->setName($name)
                ->setAddress($faker->streetAddress())
                ->setPostalCode($faker->postcode())
                ->setCity($faker->city())
                ->setCountry('FR')
                ->setEmail($faker->companyEmail())
                ->setPhone($faker->phoneNumber())
                ->setWebsite('https://www.' . $faker->domainName());

            $manager->persist($organization);

            $this->addReference($reference, $organization);
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

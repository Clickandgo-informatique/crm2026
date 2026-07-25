<?php

namespace App\DataFixtures;

use App\Entity\Tenant;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TenantFixtures extends Fixture
{
    public const TENANT_1 = 'tenant_1';
    public const TENANT_2 = 'tenant_2';
    public const TENANT_3 = 'tenant_3';
    public const TENANT_4 = 'tenant_4';

    public function load(ObjectManager $manager): void
    {
        $tenants = [
            [
                'reference' => self::TENANT_1,
                'name' => 'Garage Dupont',
                'code' => 'GARAGE_DUPONT',
                'slug' => 'garage-dupont',
                'email' => 'contact@garage-dupont.fr',
                'phone' => '0144556677',
                'website' => 'https://www.garage-dupont.fr',
                'primaryColor' => '#1F4E79',
                'secondaryColor' => '#4F81BD',
            ],
            [
                'reference' => self::TENANT_2,
                'name' => 'Ecole primaire Le Lys',
                'code' => 'ECOLE_LE_LYS',
                'slug' => 'ecole-primaire-le-lys',
                'email' => 'contact@ecole-lelys.fr',
                'phone' => '0144556678',
                'website' => 'https://www.ecole-lelys.fr',
                'primaryColor' => '#2E7D32',
                'secondaryColor' => '#66BB6A',
            ],
            [
                'reference' => self::TENANT_3,
                'name' => 'Cabinet médical Medic-Aide',
                'code' => 'MEDIC_AIDE',
                'slug' => 'medic-aide',
                'email' => 'contact@medic-aide.fr',
                'phone' => '0144556679',
                'website' => 'https://www.medic-aide.fr',
                'primaryColor' => '#00695C',
                'secondaryColor' => '#26A69A',
            ],
            [
                'reference' => self::TENANT_4,
                'name' => 'Conciergerie Ultima Paris',
                'code' => 'ULTIMA_PARIS',
                'slug' => 'ultima-paris',
                'email' => 'contact@ultima-paris.fr',
                'phone' => '0144556680',
                'website' => 'https://www.ultima-paris.fr',
                'primaryColor' => '#6A1B9A',
                'secondaryColor' => '#AB47BC',
            ],
        ];

        foreach ($tenants as $data) {
            $tenant = new Tenant();

            $tenant
                ->setName($data['name'])
                ->setCode($data['code'])
                ->setSlug($data['slug'])
                ->setEmail($data['email'])
                ->setPhone($data['phone'])
                ->setWebsite($data['website'])
                ->setPrimaryColor($data['primaryColor'])
                ->setSecondaryColor($data['secondaryColor'])
                ->setTimezone('Europe/Paris')
                ->setLocale('fr_FR')
                ->setEnabled(true);

            $manager->persist($tenant);

            $this->addReference(
                $data['reference'],
                $tenant
            );
        }

        $manager->flush();
    }
}

<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public const ADMIN = 'user.admin';

    public const GARAGE_ADMIN = 'user.garage.admin';
    public const GARAGE_TECHNICIAN = 'user.garage.technician';
    public const GARAGE_ASSISTANT = 'user.garage.assistant';

    public const SCHOOL_ADMIN = 'user.school.admin';
    public const SCHOOL_SECRETARY = 'user.school.secretary';
    public const SCHOOL_TEACHER = 'user.school.teacher';

    public const MEDICAL_ADMIN = 'user.medical.admin';
    public const MEDICAL_DOCTOR = 'user.medical.doctor';
    public const MEDICAL_ASSISTANT = 'user.medical.assistant';

    public const CONCIERGE_ADMIN = 'user.concierge.admin';
    public const CONCIERGE_MANAGER = 'user.concierge.manager';
    public const CONCIERGE_AGENT = 'user.concierge.agent';

    public function __construct(
        private readonly UserPasswordHasherInterface $passwordHasher,
    ) {}

    public function load(ObjectManager $manager): void
    {
        $users = [
            [self::ADMIN, 'admin@crm2026.local', ['ROLE_SUPER_ADMIN']],

            [self::GARAGE_ADMIN, 'admin@garage-dupont.fr', ['ROLE_ADMIN']],
            [self::GARAGE_TECHNICIAN, 'technicien@garage-dupont.fr', []],
            [self::GARAGE_ASSISTANT, 'accueil@garage-dupont.fr', []],

            [self::SCHOOL_ADMIN, 'direction@lelys.fr', ['ROLE_ADMIN']],
            [self::SCHOOL_SECRETARY, 'secretariat@lelys.fr', []],
            [self::SCHOOL_TEACHER, 'enseignant@lelys.fr', []],

            [self::MEDICAL_ADMIN, 'direction@medicaide.fr', ['ROLE_ADMIN']],
            [self::MEDICAL_DOCTOR, 'medecin@medicaide.fr', []],
            [self::MEDICAL_ASSISTANT, 'assistant@medicaide.fr', []],

            [self::CONCIERGE_ADMIN, 'direction@ultima.fr', ['ROLE_ADMIN']],
            [self::CONCIERGE_MANAGER, 'manager@ultima.fr', []],
            [self::CONCIERGE_AGENT, 'agent@ultima.fr', []],
        ];

        foreach ($users as [$reference, $email, $roles]) {
            $user = new User();
            $user
                ->setEmail($email)
                ->setRoles($roles)
                ->setPassword(
                    $this->passwordHasher->hashPassword(
                        $user,
                        'password'
                    )
                )
                ->setIsVerified(true);

            $manager->persist($user);

            $this->addReference($reference, $user);
        }

        $manager->flush();
    }
}

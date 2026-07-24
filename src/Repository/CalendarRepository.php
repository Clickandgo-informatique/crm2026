<?php

namespace App\Repository;

use App\Entity\Calendar;
use App\Entity\Tenant;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class CalendarRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Calendar::class);
    }

    /**
     * Retourne les calendriers visibles d'un tenant
     *
     * @return Calendar[]
     */
    public function findVisibleByTenant(Tenant $tenant): array
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.tenant = :tenant')
            ->andWhere('c.visible = true')
            ->setParameter('tenant', $tenant)
            ->orderBy('c.name', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Retourne les calendriers appartenant à un utilisateur
     *
     * @return Calendar[]
     */
    public function findByOwner(int $userId): array
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.owner = :owner')
            ->setParameter('owner', $userId)
            ->orderBy('c.name', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Retourne le calendrier par défaut d'un tenant
     */
    public function findDefaultByTenant(Tenant $tenant): ?Calendar
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.tenant = :tenant')
            ->andWhere('c.isDefault = true')
            ->setParameter('tenant', $tenant)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * Retourne les calendriers personnels d'un utilisateur
     *
     * @return Calendar[]
     */
    public function findPersonalByOwner(int $userId): array
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.owner = :owner')
            ->andWhere('c.type = :type')
            ->setParameter('owner', $userId)
            ->setParameter('type', 'personal')
            ->orderBy('c.name', 'ASC')
            ->getQuery()
            ->getResult();
    }
}

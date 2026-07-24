<?php

namespace App\Repository;

use App\Entity\Tenant;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class TenantRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tenant::class);
    }

    /**
     * Retourne les tenants actifs
     *
     * @return Tenant[]
     */
    public function findActive(): array
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.enabled = true')
            ->orderBy('t.name', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Recherche un tenant par son slug
     */
    public function findOneBySlug(string $slug): ?Tenant
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.slug = :slug')
            ->setParameter('slug', strtolower(trim($slug)))
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * Recherche un tenant par son code
     */
    public function findOneByCode(string $code): ?Tenant
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.code = :code')
            ->setParameter('code', strtoupper(trim($code)))
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * Retourne un tenant avec ses organisations chargées
     */
    public function findWithOrganizations(int $id): ?Tenant
    {
        return $this->createQueryBuilder('t')
            ->leftJoin('t.organizations', 'o')
            ->addSelect('o')
            ->andWhere('t.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * Retourne un tenant complet avec ses calendriers
     */
    public function findWithCalendars(int $id): ?Tenant
    {
        return $this->createQueryBuilder('t')
            ->leftJoin('t.calendars', 'c')
            ->addSelect('c')
            ->andWhere('t.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * Vérifie si un tenant actif existe
     */
    public function existsActive(string $slug): bool
    {
        return (bool) $this->createQueryBuilder('t')
            ->select('COUNT(t.id)')
            ->andWhere('t.slug = :slug')
            ->andWhere('t.enabled = true')
            ->setParameter('slug', strtolower(trim($slug)))
            ->getQuery()
            ->getSingleScalarResult();
    }
}

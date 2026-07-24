<?php

namespace App\Repository;

use App\Entity\Organization;
use App\Entity\Tenant;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class OrganizationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Organization::class);
    }

    /**
     * Retourne les organisations actives d'un tenant
     *
     * @return Organization[]
     */
    public function findByTenant(Tenant $tenant): array
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.tenant = :tenant')
            ->setParameter('tenant', $tenant)
            ->orderBy('o.name', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Recherche par nom dans un tenant
     *
     * @return Organization[]
     */
    public function searchByName(Tenant $tenant, string $search): array
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.tenant = :tenant')
            ->andWhere('LOWER(o.name) LIKE LOWER(:search)')
            ->setParameter('tenant', $tenant)
            ->setParameter('search', '%' . $search . '%')
            ->orderBy('o.name', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Charge une organisation avec ses intervenants
     */
    public function findWithStaffMembers(int $id): ?Organization
    {
        return $this->createQueryBuilder('o')
            ->leftJoin('o.organizationMembers', 'om')
            ->leftJoin('om.staffMember', 's')
            ->addSelect('om', 's')
            ->andWhere('o.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }
}

<?php

namespace App\Repository;

use App\Entity\Organization;
use App\Entity\StaffMember;
use App\Entity\Tenant;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class StaffMemberRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StaffMember::class);
    }

    /**
     * Retourne les intervenants actifs d'un tenant
     *
     * @return StaffMember[]
     */
    public function findActiveByTenant(Tenant $tenant): array
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.tenant = :tenant')
            ->andWhere('s.active = true')
            ->setParameter('tenant', $tenant)
            ->orderBy('s.lastName', 'ASC')
            ->addOrderBy('s.firstName', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Retourne les intervenants d'une organisation
     *
     * @return StaffMember[]
     */
    public function findByOrganization(Organization $organization): array
    {
        return $this->createQueryBuilder('s')
            ->innerJoin('s.organizations', 'o')
            ->andWhere('o = :organization')
            ->andWhere('s.active = true')
            ->setParameter('organization', $organization)
            ->orderBy('s.lastName', 'ASC')
            ->addOrderBy('s.firstName', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Recherche d'intervenants par nom ou prénom
     *
     * @return StaffMember[]
     */
    public function searchByName(Tenant $tenant, string $search): array
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.tenant = :tenant')
            ->andWhere(
                'LOWER(s.firstName) LIKE LOWER(:search)
                OR LOWER(s.lastName) LIKE LOWER(:search)'
            )
            ->andWhere('s.active = true')
            ->setParameter('tenant', $tenant)
            ->setParameter('search', '%' . $search . '%')
            ->orderBy('s.lastName', 'ASC')
            ->addOrderBy('s.firstName', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Retourne un intervenant avec ses organisations chargées
     */
    public function findWithOrganizations(int $id): ?StaffMember
    {
        return $this->createQueryBuilder('s')
            ->leftJoin('s.organizations', 'o')
            ->addSelect('o')
            ->andWhere('s.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
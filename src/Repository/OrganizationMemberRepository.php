<?php

namespace App\Repository;

use App\Entity\Organization;
use App\Entity\OrganizationMember;
use App\Entity\StaffMember;
use App\Entity\Tenant;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class OrganizationMemberRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrganizationMember::class);
    }


    /**
     * Retourne les membres actifs d'une organisation
     *
     * @return OrganizationMember[]
     */
    public function findActiveByOrganization(Organization $organization): array
    {
        return $this->createQueryBuilder('om')
            ->innerJoin('om.staffMember', 's')
            ->addSelect('s')
            ->andWhere('om.organization = :organization')
            ->andWhere('om.active = true')
            ->setParameter('organization', $organization)
            ->orderBy('s.lastName', 'ASC')
            ->addOrderBy('s.firstName', 'ASC')
            ->getQuery()
            ->getResult();
    }


    /**
     * Retourne les organisations actives d'un intervenant
     *
     * @return OrganizationMember[]
     */
    public function findActiveByStaffMember(StaffMember $staffMember): array
    {
        return $this->createQueryBuilder('om')
            ->innerJoin('om.organization', 'o')
            ->addSelect('o')
            ->andWhere('om.staffMember = :staffMember')
            ->andWhere('om.active = true')
            ->setParameter('staffMember', $staffMember)
            ->orderBy('o.name', 'ASC')
            ->getQuery()
            ->getResult();
    }


    /**
     * Retourne les affectations d'un tenant
     *
     * @return OrganizationMember[]
     */
    public function findByTenant(Tenant $tenant): array
    {
        return $this->createQueryBuilder('om')
            ->innerJoin('om.organization', 'o')
            ->innerJoin('om.staffMember', 's')
            ->addSelect('o', 's')
            ->andWhere('o.tenant = :tenant')
            ->setParameter('tenant', $tenant)
            ->orderBy('o.name', 'ASC')
            ->addOrderBy('s.lastName', 'ASC')
            ->getQuery()
            ->getResult();
    }


    /**
     * Vérifie si un intervenant appartient déjà à une organisation
     */
    public function exists(
        Organization $organization,
        StaffMember $staffMember
    ): bool {
        return (bool) $this->createQueryBuilder('om')
            ->select('COUNT(om.id)')
            ->andWhere('om.organization = :organization')
            ->andWhere('om.staffMember = :staffMember')
            ->setParameter('organization', $organization)
            ->setParameter('staffMember', $staffMember)
            ->getQuery()
            ->getSingleScalarResult();
    }


    /**
     * Charge une affectation avec toutes ses relations
     */
    public function findComplete(int $id): ?OrganizationMember
    {
        return $this->createQueryBuilder('om')
            ->innerJoin('om.organization', 'o')
            ->innerJoin('om.staffMember', 's')
            ->addSelect('o', 's')
            ->andWhere('om.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }


    /**
     * Retourne les intervenants disponibles pour un planning
     *
     * Préparation pour les futures disponibilités
     *
     * @return OrganizationMember[]
     */
    public function findAvailableForOrganization(
        Organization $organization
    ): array {
        return $this->createQueryBuilder('om')
            ->innerJoin('om.staffMember', 's')
            ->addSelect('s')
            ->andWhere('om.organization = :organization')
            ->andWhere('om.active = true')
            ->andWhere('s.active = true')
            ->setParameter('organization', $organization)
            ->orderBy('s.lastName', 'ASC')
            ->addOrderBy('s.firstName', 'ASC')
            ->getQuery()
            ->getResult();
    }
}

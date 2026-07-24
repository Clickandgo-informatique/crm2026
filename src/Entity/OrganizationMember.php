<?php

namespace App\Entity;

use App\Entity\Traits\TimestampableTrait;
use App\Repository\OrganizationMemberRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: OrganizationMemberRepository::class)]
#[ORM\Table(name: 'organization_member')]
#[UniqueEntity(
    fields: ['organization', 'staffMember'],
    message: 'Cet intervenant est déjà associé à cette organisation.'
)]
class OrganizationMember
{
    use TimestampableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;


    #[ORM\ManyToOne(
        inversedBy: 'organizationMembers'
    )]
    #[ORM\JoinColumn(nullable: false)]
    private ?Organization $organization = null;


    #[ORM\ManyToOne(
        inversedBy: 'organizationMembers'
    )]
    #[ORM\JoinColumn(nullable: false)]
    private ?StaffMember $staffMember = null;


    #[ORM\Column(length: 100)]
    #[Assert\NotBlank(
        message: 'Le rôle de l’intervenant est obligatoire.'
    )]
    #[Assert\Length(
        min: 2,
        max: 100,
        minMessage: 'Le rôle doit contenir au moins {{ limit }} caractères.',
        maxMessage: 'Le rôle ne peut pas dépasser {{ limit }} caractères.'
    )]
    private ?string $role = null;


    #[ORM\Column]
    private bool $active = true;


    #[ORM\Column(nullable: true)]
    #[Assert\PositiveOrZero(
        message: 'Le taux horaire doit être positif ou nul.'
    )]
    private ?float $hourlyRate = null;


    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $startDate = null;


    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $endDate = null;


    public function __toString(): string
    {
        return $this->staffMember?->getFullName() ?? '';
    }


    public function getId(): ?int
    {
        return $this->id;
    }


    public function getOrganization(): ?Organization
    {
        return $this->organization;
    }


    public function setOrganization(?Organization $organization): static
    {
        $this->organization = $organization;

        return $this;
    }


    public function getStaffMember(): ?StaffMember
    {
        return $this->staffMember;
    }


    public function setStaffMember(?StaffMember $staffMember): static
    {
        $this->staffMember = $staffMember;

        return $this;
    }


    public function getRole(): ?string
    {
        return $this->role;
    }


    public function setRole(string $role): static
    {
        $this->role = trim($role);

        return $this;
    }


    public function isActive(): bool
    {
        return $this->active;
    }


    public function setActive(bool $active): static
    {
        $this->active = $active;

        return $this;
    }


    public function getHourlyRate(): ?float
    {
        return $this->hourlyRate;
    }


    public function setHourlyRate(?float $hourlyRate): static
    {
        $this->hourlyRate = $hourlyRate;

        return $this;
    }


    public function getStartDate(): ?\DateTimeImmutable
    {
        return $this->startDate;
    }


    public function setStartDate(?\DateTimeImmutable $startDate): static
    {
        $this->startDate = $startDate;

        return $this;
    }


    public function getEndDate(): ?\DateTimeImmutable
    {
        return $this->endDate;
    }


    public function setEndDate(?\DateTimeImmutable $endDate): static
    {
        $this->endDate = $endDate;

        return $this;
    }
}

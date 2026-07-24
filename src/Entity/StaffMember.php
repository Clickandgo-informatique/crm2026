<?php

namespace App\Entity;

use App\Entity\Traits\TimestampableTrait;
use App\Repository\StaffMemberRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: StaffMemberRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[UniqueEntity(
    fields: ['tenant', 'email'],
    message: 'Un intervenant avec cette adresse e-mail existe déjà dans ce tenant.'
)]
class StaffMember
{
    use TimestampableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;


    #[ORM\Column(length: 100)]
    #[Assert\NotBlank(
        message: 'Le prénom est obligatoire.'
    )]
    private string $firstName;


    #[ORM\Column(length: 100)]
    #[Assert\NotBlank(
        message: 'Le nom est obligatoire.'
    )]
    private string $lastName;


    #[ORM\Column(length: 180, nullable: true)]
    #[Assert\Email(
        message: 'L’adresse e-mail est invalide.'
    )]
    private ?string $email = null;


    #[ORM\Column(length: 30, nullable: true)]
    private ?string $phone = null;


    #[ORM\Column]
    private bool $active = true;


    #[ORM\ManyToOne(
        inversedBy: 'staffMembers'
    )]
    #[ORM\JoinColumn(nullable: false)]
    private ?Tenant $tenant = null;


    #[ORM\OneToMany(
        mappedBy: 'staffMember',
        targetEntity: OrganizationMember::class,
        orphanRemoval: true
    )]
    private Collection $organizationMembers;


    public function __construct()
    {
        $this->organizationMembers = new ArrayCollection();
    }


    public function __toString(): string
    {
        return $this->getFullName();
    }


    public function getId(): ?int
    {
        return $this->id;
    }


    public function getFirstName(): string
    {
        return $this->firstName;
    }


    public function setFirstName(string $firstName): static
    {
        $this->firstName = trim($firstName);

        return $this;
    }


    public function getLastName(): string
    {
        return $this->lastName;
    }


    public function setLastName(string $lastName): static
    {
        $this->lastName = trim($lastName);

        return $this;
    }


    public function getFullName(): string
    {
        return trim($this->firstName . ' ' . $this->lastName);
    }


    public function getEmail(): ?string
    {
        return $this->email;
    }


    public function setEmail(?string $email): static
    {
        $this->email = $email ? strtolower(trim($email)) : null;

        return $this;
    }


    public function getPhone(): ?string
    {
        return $this->phone;
    }


    public function setPhone(?string $phone): static
    {
        $this->phone = $phone ? trim($phone) : null;

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


    public function getTenant(): ?Tenant
    {
        return $this->tenant;
    }


    public function setTenant(?Tenant $tenant): static
    {
        $this->tenant = $tenant;

        return $this;
    }


    /**
     * @return Collection<int, OrganizationMember>
     */
    public function getOrganizationMembers(): Collection
    {
        return $this->organizationMembers;
    }


    public function addOrganizationMember(OrganizationMember $member): static
    {
        if (!$this->organizationMembers->contains($member)) {
            $this->organizationMembers->add($member);
            $member->setStaffMember($this);
        }

        return $this;
    }


    public function removeOrganizationMember(OrganizationMember $member): static
    {
        if ($this->organizationMembers->removeElement($member)) {
            if ($member->getStaffMember() === $this) {
                $member->setStaffMember(null);
            }
        }

        return $this;
    }
}

<?php

namespace App\Entity;

use App\Entity\Traits\TimestampableTrait;
use App\Repository\OrganizationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: OrganizationRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[ORM\Index(columns: ['tenant_id', 'name'])]
#[UniqueEntity(
    fields: ['tenant', 'name'],
    message: 'Une organisation portant ce nom existe déjà dans ce tenant.'
)]
class Organization
{
    use TimestampableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;


    #[ORM\Column(length: 150)]
    #[Assert\NotBlank(
        message: 'Le nom de l’organisation est obligatoire.'
    )]
    #[Assert\Length(
        min: 2,
        max: 150,
        minMessage: 'Le nom de l’organisation doit contenir au moins {{ limit }} caractères.',
        maxMessage: 'Le nom de l’organisation ne peut pas dépasser {{ limit }} caractères.'
    )]
    private string $name;


    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Length(
        max: 255,
        message: 'L’adresse ne peut pas dépasser {{ limit }} caractères.'
    )]
    private ?string $address = null;


    #[ORM\Column(length: 100, nullable: true)]
    private ?string $city = null;


    #[ORM\Column(length: 20, nullable: true)]
    private ?string $postalCode = null;


    #[ORM\Column(length: 2, nullable: true)]
    #[Assert\Country(
        message: 'Le pays sélectionné est invalide.'
    )]
    private ?string $country = null;


    #[ORM\Column(length: 180, nullable: true)]
    #[Assert\Email(
        message: 'L’adresse e-mail est invalide.'
    )]
    private ?string $email = null;


    #[ORM\Column(length: 30, nullable: true)]
    private ?string $phone = null;


    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Url(
        message: 'Le site internet doit être une URL valide.'
    )]
    private ?string $website = null;


    #[ORM\ManyToOne(
        inversedBy: 'organizations'
    )]
    #[ORM\JoinColumn(nullable: false)]
    private ?Tenant $tenant = null;


    #[ORM\OneToMany(
        mappedBy: 'organization',
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
        return $this->name ?? '';
    }


    public function getId(): ?int
    {
        return $this->id;
    }


    public function getName(): string
    {
        return $this->name;
    }


    public function setName(string $name): static
    {
        $this->name = trim($name);

        return $this;
    }


    public function getAddress(): ?string
    {
        return $this->address;
    }


    public function setAddress(?string $address): static
    {
        $this->address = $address ? trim($address) : null;

        return $this;
    }


    public function getCity(): ?string
    {
        return $this->city;
    }


    public function setCity(?string $city): static
    {
        $this->city = $city ? trim($city) : null;

        return $this;
    }


    public function getPostalCode(): ?string
    {
        return $this->postalCode;
    }


    public function setPostalCode(?string $postalCode): static
    {
        $this->postalCode = $postalCode ? trim($postalCode) : null;

        return $this;
    }


    public function getCountry(): ?string
    {
        return $this->country;
    }


    public function setCountry(?string $country): static
    {
        $this->country = $country ? strtoupper(trim($country)) : null;

        return $this;
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


    public function getWebsite(): ?string
    {
        return $this->website;
    }


    public function setWebsite(?string $website): static
    {
        $this->website = $website ? trim($website) : null;

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
            $member->setOrganization($this);
        }

        return $this;
    }


    public function removeOrganizationMember(OrganizationMember $member): static
    {
        if ($this->organizationMembers->removeElement($member)) {
            if ($member->getOrganization() === $this) {
                $member->setOrganization(null);
            }
        }

        return $this;
    }
}

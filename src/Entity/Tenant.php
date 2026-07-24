<?php

namespace App\Entity;

use App\Entity\Traits\TimestampableTrait;
use App\Repository\TenantRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: TenantRepository::class)]
#[ORM\Table(name: 'tenant')]
#[UniqueEntity(
    fields: ['name'],
    message: 'Un tenant portant ce nom existe déjà.'
)]
#[UniqueEntity(
    fields: ['slug'],
    message: 'Ce slug est déjà utilisé.'
)]
#[UniqueEntity(
    fields: ['code'],
    message: 'Ce code est déjà utilisé.'
)]
class Tenant
{
    use TimestampableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 150, unique: true)]
    #[Assert\NotBlank(
        message: 'Le nom du tenant est obligatoire.'
    )]
    #[Assert\Length(
        min: 2,
        max: 150,
        minMessage: 'Le nom du tenant doit contenir au moins {{ limit }} caractères.',
        maxMessage: 'Le nom du tenant ne peut pas dépasser {{ limit }} caractères.'
    )]
    private ?string $name = null;

    #[ORM\Column(length: 50, unique: true)]
    #[Assert\NotBlank(
        message: 'Le code du tenant est obligatoire.'
    )]
    #[Assert\Length(
        min: 2,
        max: 50,
        minMessage: 'Le code doit contenir au moins {{ limit }} caractères.',
        maxMessage: 'Le code ne peut pas dépasser {{ limit }} caractères.'
    )]
    #[Assert\Regex(
        pattern: '/^[A-Z0-9_-]+$/',
        message: 'Le code ne peut contenir que des lettres majuscules, des chiffres, des tirets et des underscores.'
    )]
    private ?string $code = null;

    #[ORM\Column(length: 150, unique: true)]
    #[Assert\NotBlank(
        message: 'Le slug est obligatoire.'
    )]
    #[Assert\Length(
        min: 2,
        max: 150,
        minMessage: 'Le slug doit contenir au moins {{ limit }} caractères.',
        maxMessage: 'Le slug ne peut pas dépasser {{ limit }} caractères.'
    )]
    #[Assert\Regex(
        pattern: '/^[a-z0-9]+(?:-[a-z0-9]+)*$/',
        message: 'Le slug ne peut contenir que des lettres minuscules, des chiffres et des tirets.'
    )]
    private ?string $slug = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Length(
        max: 255,
        maxMessage: 'Le nom du fichier du logo ne peut pas dépasser {{ limit }} caractères.'
    )]
    private ?string $logo = null;

    #[ORM\Column(length: 7)]
    #[Assert\NotBlank(
        message: 'La couleur principale est obligatoire.'
    )]
    #[Assert\Regex(
        pattern: '/^#[0-9A-Fa-f]{6}$/',
        message: 'La couleur principale doit être au format #RRGGBB.'
    )]
    private string $primaryColor = '#1F4E79';

    #[ORM\Column(length: 7)]
    #[Assert\NotBlank(
        message: 'La couleur secondaire est obligatoire.'
    )]
    #[Assert\Regex(
        pattern: '/^#[0-9A-Fa-f]{6}$/',
        message: 'La couleur secondaire doit être au format #RRGGBB.'
    )]
    private string $secondaryColor = '#4F81BD';

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Url(
        message: 'Le site internet doit être une URL valide.'
    )]
    #[Assert\Length(
        max: 255,
        maxMessage: 'Le site internet ne peut pas dépasser {{ limit }} caractères.'
    )]
    private ?string $website = null;

    #[ORM\Column(length: 180)]
    #[Assert\NotBlank(
        message: 'L’adresse e-mail est obligatoire.'
    )]
    #[Assert\Email(
        message: 'L’adresse e-mail est invalide.'
    )]
    #[Assert\Length(
        max: 180,
        maxMessage: 'L’adresse e-mail ne peut pas dépasser {{ limit }} caractères.'
    )]
    private ?string $email = null;

    #[ORM\Column(length: 30, nullable: true)]
    #[Assert\Length(
        max: 30,
        maxMessage: 'Le numéro de téléphone ne peut pas dépasser {{ limit }} caractères.'
    )]
    private ?string $phone = null;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank(
        message: 'Le fuseau horaire est obligatoire.'
    )]
    #[Assert\Timezone(
        message: 'Le fuseau horaire sélectionné est invalide.'
    )]
    private string $timezone = 'Europe/Paris';

    #[ORM\Column(length: 10)]
    #[Assert\NotBlank(
        message: 'La langue est obligatoire.'
    )]
    #[Assert\Locale(
        message: 'La langue sélectionnée est invalide.'
    )]
    private string $locale = 'fr_FR';

    #[ORM\Column]
    private bool $enabled = true;

    #[ORM\OneToMany(
        mappedBy: 'tenant',
        targetEntity: Organization::class
    )]
    private Collection $organizations;

    #[ORM\OneToMany(
        mappedBy: 'tenant',
        targetEntity: Calendar::class
    )]
    private Collection $calendars;

    #[ORM\OneToMany(
        mappedBy: 'tenant',
        targetEntity: StaffMember::class
    )]
    private Collection $staffMembers;

    public function __construct()
    {
        $this->organizations = new ArrayCollection();
        $this->calendars = new ArrayCollection();
        $this->staffMembers = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->name ?? '';
    }

    // getters et setters...
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = trim($name);

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): static
    {
        $this->code = strtoupper(trim($code));

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = strtolower(trim($slug));

        return $this;
    }

    public function getLogo(): ?string
    {
        return $this->logo;
    }

    public function setLogo(?string $logo): static
    {
        $this->logo = $logo;

        return $this;
    }

    public function getPrimaryColor(): string
    {
        return $this->primaryColor;
    }

    public function setPrimaryColor(string $primaryColor): static
    {
        $this->primaryColor = strtoupper($primaryColor);

        return $this;
    }

    public function getSecondaryColor(): string
    {
        return $this->secondaryColor;
    }

    public function setSecondaryColor(string $secondaryColor): static
    {
        $this->secondaryColor = strtoupper($secondaryColor);

        return $this;
    }

    public function getWebsite(): ?string
    {
        return $this->website;
    }

    public function setWebsite(?string $website): static
    {
        $this->website = $website;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = strtolower(trim($email));

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): static
    {
        $this->phone = $phone;

        return $this;
    }

    public function getTimezone(): string
    {
        return $this->timezone;
    }

    public function setTimezone(string $timezone): static
    {
        $this->timezone = $timezone;

        return $this;
    }

    public function getLocale(): string
    {
        return $this->locale;
    }

    public function setLocale(string $locale): static
    {
        $this->locale = $locale;

        return $this;
    }

    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    public function getEnabled(): bool
    {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled): static
    {
        $this->enabled = $enabled;

        return $this;
    }

    /**
     * @return Collection<int, Organization>
     */
    public function getOrganizations(): Collection
    {
        return $this->organizations;
    }

    public function addOrganization(Organization $organization): static
    {
        if (!$this->organizations->contains($organization)) {
            $this->organizations->add($organization);
            $organization->setTenant($this);
        }

        return $this;
    }

    public function removeOrganization(Organization $organization): static
    {
        if ($this->organizations->removeElement($organization)) {
            if ($organization->getTenant() === $this) {
                $organization->setTenant(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Calendar>
     */
    public function getCalendars(): Collection
    {
        return $this->calendars;
    }

    public function addCalendar(Calendar $calendar): static
    {
        if (!$this->calendars->contains($calendar)) {
            $this->calendars->add($calendar);
            $calendar->setTenant($this);
        }

        return $this;
    }

    public function removeCalendar(Calendar $calendar): static
    {
        if ($this->calendars->removeElement($calendar)) {
            if ($calendar->getTenant() === $this) {
                $calendar->setTenant(null);
            }
        }

        return $this;
    }
    /**
     * @return Collection<int, StaffMember>
     */
    public function getStaffMembers(): Collection
    {
        return $this->staffMembers;
    }

    public function addStaffMember(StaffMember $staffMember): static
    {
        if (!$this->staffMembers->contains($staffMember)) {
            $this->staffMembers->add($staffMember);
            $staffMember->setTenant($this);
        }

        return $this;
    }

    public function removeStaffMember(StaffMember $staffMember): static
    {
        $this->staffMembers->removeElement($staffMember);

        return $this;
    }
}

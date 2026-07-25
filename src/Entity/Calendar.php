<?php

namespace App\Entity;

use App\Entity\Traits\TimestampableTrait;
use App\Entity\Enum\CalendarType;
use App\Repository\CalendarRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CalendarRepository::class)]
#[ORM\Table(name: 'calendar')]
#[UniqueEntity(
    fields: ['tenant', 'name'],
    message: 'Un calendrier portant ce nom existe déjà dans ce tenant.'
)]
#[ORM\HasLifecycleCallbacks]
class Calendar
{
    use TimestampableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(
        inversedBy: 'calendars'
    )]
    #[ORM\JoinColumn(nullable: false)]
    private ?Tenant $tenant = null;

    #[ORM\ManyToOne]
    private ?User $owner = null;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank(
        message: 'Le nom du calendrier est obligatoire.'
    )]
    #[Assert\Length(
        min: 2,
        max: 100,
        minMessage: 'Le nom du calendrier doit contenir au moins {{ limit }} caractères.',
        maxMessage: 'Le nom du calendrier ne peut pas dépasser {{ limit }} caractères.'
    )]
    private ?string $name = null;

    #[ORM\Column(type: 'text', nullable: true)]
    #[Assert\Length(
        max: 2000,
        maxMessage: 'La description ne peut pas dépasser {{ limit }} caractères.'
    )]
    private ?string $description = null;

    #[ORM\Column(enumType: CalendarType::class)]
    private CalendarType $type = CalendarType::PERSONAL;

    #[ORM\Column(length: 7)]
    #[Assert\NotBlank(
        message: 'La couleur du calendrier est obligatoire.'
    )]
    #[Assert\Regex(
        pattern: '/^#[0-9A-Fa-f]{6}$/',
        message: 'La couleur doit être au format hexadécimal #RRGGBB.'
    )]
    private string $color = '#3788D8';

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank(
        message: 'Le fuseau horaire est obligatoire.'
    )]
    #[Assert\Timezone(
        message: 'Le fuseau horaire sélectionné est invalide.'
    )]
    private string $timezone = 'Europe/Paris';

    #[ORM\Column]
    private bool $visible = true;

    #[ORM\Column]
    private bool $isDefault = false;

    #[ORM\Column]
    private bool $isReadOnly = false;

    #[ORM\OneToMany(
        mappedBy: 'calendar',
        targetEntity: CalendarEvent::class
    )]
    private Collection $events;

    public function __construct()
    {
        $this->events = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->name ?? '';
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): static
    {
        $this->owner = $owner;

        return $this;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getType(): CalendarType
    {
        return $this->type;
    }

    public function setType(CalendarType $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getColor(): string
    {
        return $this->color;
    }

    public function setColor(string $color): static
    {
        $this->color = strtoupper($color);

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

    public function isVisible(): bool
    {
        return $this->visible;
    }

    public function setVisible(bool $visible): static
    {
        $this->visible = $visible;

        return $this;
    }

    public function isDefault(): bool
    {
        return $this->isDefault;
    }

    public function setDefault(bool $isDefault): static
    {
        $this->isDefault = $isDefault;

        return $this;
    }

    public function isReadOnly(): bool
    {
        return $this->isReadOnly;
    }

    public function setReadOnly(bool $isReadOnly): static
    {
        $this->isReadOnly = $isReadOnly;

        return $this;
    }

    /**
     * @return Collection<int, CalendarEvent>
     */
    public function getEvents(): Collection
    {
        return $this->events;
    }

    public function addEvent(CalendarEvent $event): static
    {
        if (!$this->events->contains($event)) {
            $this->events->add($event);
            $event->setCalendar($this);
        }

        return $this;
    }

    public function removeEvent(CalendarEvent $event): static
    {
        if ($this->events->removeElement($event)) {
            if ($event->getCalendar() === $this) {
                $event->setCalendar(null);
            }
        }

        return $this;
    }
}

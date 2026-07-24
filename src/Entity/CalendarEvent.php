<?php

namespace App\Entity;

use App\Entity\Enum\EventStatus;
use App\Entity\Enum\EventType;
use App\Entity\Traits\TimestampableTrait;
use App\Repository\CalendarEventRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CalendarEventRepository::class)]
#[ORM\Index(
    columns: ['start_at', 'end_at']
)]
#[ORM\HasLifecycleCallbacks]
class CalendarEvent
{
    use TimestampableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotBlank(
        message: 'Le titre de l’événement est obligatoire.'
    )]
    #[Assert\Length(
        min: 3,
        max: 150,
        minMessage: 'Le titre doit contenir au moins {{ limit }} caractères.',
        maxMessage: 'Le titre ne peut pas dépasser {{ limit }} caractères.'
    )]
    #[ORM\Column(length: 150)]
    private string $title;

    #[Assert\Length(
        max: 5000,
        maxMessage: 'La description ne peut pas dépasser {{ limit }} caractères.'
    )]
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[Assert\NotNull(
        message: 'La date de début est obligatoire.'
    )]
    #[ORM\Column]
    private ?\DateTimeImmutable $startAt = null;

    #[Assert\NotNull(
        message: 'La date de fin est obligatoire.'
    )]
    #[ORM\Column]
    private ?\DateTimeImmutable $endAt = null;

    #[ORM\Column]
    private bool $allDay = false;

    #[ORM\ManyToOne(
        inversedBy: 'events'
    )]
    #[ORM\JoinColumn(nullable: false)]
    private ?Calendar $calendar = null;

    #[ORM\ManyToOne]
    private ?Organization $organization = null;

    #[ORM\ManyToOne]
    private ?StaffMember $staffMember = null;

    #[ORM\ManyToOne]
    private ?User $assignedTo = null;

    #[ORM\ManyToOne]
    private ?Dossier $dossier = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Length(
        max: 255,
        maxMessage: 'L’identifiant externe ne peut pas dépasser {{ limit }} caractères.'
    )]
    private ?string $externalId = null;

    #[ORM\Column(enumType: EventStatus::class)]
    private EventStatus $status = EventStatus::PLANNED;

    #[ORM\Column(enumType: EventType::class)]
    private EventType $type = EventType::APPOINTMENT;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = trim($title);

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

    public function getStartAt(): ?\DateTimeImmutable
    {
        return $this->startAt;
    }

    public function setStartAt(\DateTimeImmutable $startAt): static
    {
        $this->startAt = $startAt;

        return $this;
    }

    public function getEndAt(): ?\DateTimeImmutable
    {
        return $this->endAt;
    }

    public function setEndAt(\DateTimeImmutable $endAt): static
    {
        $this->endAt = $endAt;

        return $this;
    }

    public function isAllDay(): bool
    {
        return $this->allDay;
    }

    public function setAllDay(bool $allDay): static
    {
        $this->allDay = $allDay;

        return $this;
    }

    public function getCalendar(): ?Calendar
    {
        return $this->calendar;
    }

    public function setCalendar(?Calendar $calendar): static
    {
        $this->calendar = $calendar;

        return $this;
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

    public function getAssignedTo(): ?User
    {
        return $this->assignedTo;
    }

    public function setAssignedTo(?User $assignedTo): static
    {
        $this->assignedTo = $assignedTo;

        return $this;
    }

    public function getDossier(): ?Dossier
    {
        return $this->dossier;
    }

    public function setDossier(?Dossier $dossier): static
    {
        $this->dossier = $dossier;

        return $this;
    }

    public function getExternalId(): ?string
    {
        return $this->externalId;
    }

    public function setExternalId(?string $externalId): static
    {
        $this->externalId = $externalId;

        return $this;
    }

    public function getStatus(): EventStatus
    {
        return $this->status;
    }

    public function setStatus(EventStatus $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getType(): EventType
    {
        return $this->type;
    }

    public function setType(EventType $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function __toString(): string
    {
        return $this->title;
    }
}

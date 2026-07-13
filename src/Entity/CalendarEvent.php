<?php

namespace App\Entity;

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

    #[Assert\NotBlank]
    #[Assert\Length(
        min: 3,
        max: 150
    )]
    #[ORM\Column(length: 150)]
    private string $title;

    #[Assert\Length(
        max: 5000
    )]
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[Assert\NotNull]
    #[ORM\Column]
    private ?\DateTimeImmutable $startAt = null;

    #[Assert\NotNull]
    #[ORM\Column]
    private ?\DateTimeImmutable $endAt = null;

    #[ORM\Column]
    private bool $allDay = false;

    // #[ORM\ManyToOne]
    // private ?Customer $customer = null;

    #[ORM\ManyToOne]
    private ?User $assignedTo = null;

    #[ORM\ManyToOne]
    private ?Dossier $dossier = null;

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
        $this->title = $title;

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


    // public function getCustomer(): ?Customer
    // {
    //     return $this->customer;
    // }


    // public function setCustomer(?Customer $customer): static
    // {
    //     $this->customer = $customer;

    //     return $this;
    // }


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

    public function getType(): EventType
    {
        return $this->type;
    }


    public function setType(EventType $type): static
    {
        $this->type = $type;

        return $this;
    }
}

<?php

namespace App\Entity;

use App\Entity\Enum\DossierStatus;
use App\Entity\Traits\TimestampableTrait;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
#[ORM\HasLifecycleCallbacks]
class Dossier
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


    #[ORM\Column(enumType: DossierStatus::class)]
    private DossierStatus $status = DossierStatus::DRAFT;


    #[Assert\NotNull]
    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Organization $organization = null;


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


    public function getStatus(): DossierStatus
    {
        return $this->status;
    }


    public function setStatus(DossierStatus $status): static
    {
        $this->status = $status;

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
}

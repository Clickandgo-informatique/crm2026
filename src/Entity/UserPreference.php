<?php

namespace App\Entity;

use App\Repository\UserPreferenceRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserPreferenceRepository::class)]
class UserPreference
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(inversedBy: 'preference')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column(length: 64)]
    #[Assert\NotBlank(
        message: 'Le fuseau horaire est obligatoire.'
    )]
    private string $timezone = 'Europe/Paris';

    #[ORM\Column(length: 16)]
    #[Assert\NotBlank(
        message: 'La langue est obligatoire.'
    )]
    #[Assert\Locale(
        message: 'La langue "{{ value }}" n’est pas une locale valide.'
    )]
    private string $locale = 'fr_FR';

    #[ORM\Column(length: 32)]
    #[Assert\NotBlank(
        message: 'Le format de date est obligatoire.'
    )]
    private string $dateFormat = 'dd/MM/yyyy';

    #[ORM\Column(length: 32)]
    #[Assert\NotBlank(
        message: 'Le format horaire est obligatoire.'
    )]
    private string $timeFormat = 'HH:mm';

    #[ORM\Column]
    #[Assert\Range(
        min: 1,
        max: 7,
        notInRangeMessage: 'Le premier jour de la semaine doit être compris entre {{ min }} et {{ max }}.'
    )]
    private int $firstDayOfWeek = 1;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDateFormat(): string
    {
        return $this->dateFormat;
    }

    public function setDateFormat(string $dateFormat): static
    {
        $this->dateFormat = $dateFormat;

        return $this;
    }

    public function getTimeFormat(): string
    {
        return $this->timeFormat;
    }

    public function setTimeFormat(string $timeFormat): static
    {
        $this->timeFormat = $timeFormat;

        return $this;
    }

    public function getFirstDayOfWeek(): int
    {
        return $this->firstDayOfWeek;
    }

    public function setFirstDayOfWeek(int $firstDayOfWeek): static
    {
        $this->firstDayOfWeek = $firstDayOfWeek;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): static
    {
        $this->user = $user;

        return $this;
    }
}

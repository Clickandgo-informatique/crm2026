<?php

namespace App\Entity;

use App\Entity\Traits\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
#[ORM\HasLifecycleCallbacks]
class Organization
{
    use TimestampableTrait;


    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;


    #[Assert\NotBlank]
    #[Assert\Length(
        min: 2,
        max: 150
    )]
    #[ORM\Column(length: 150)]
    private string $name;


    #[Assert\Length(
        max: 255
    )]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $address = null;


    #[Assert\Length(
        max: 100
    )]
    #[ORM\Column(length: 100, nullable: true)]
    private ?string $city = null;


    #[Assert\Length(
        max: 20
    )]
    #[ORM\Column(length: 20, nullable: true)]
    private ?string $postalCode = null;


    #[Assert\Country]
    #[ORM\Column(length: 2, nullable: true)]
    private ?string $country = null;


    #[Assert\Email]
    #[ORM\Column(length: 180, nullable: true)]
    private ?string $email = null;


    #[Assert\Length(
        max: 30
    )]
    #[ORM\Column(length: 30, nullable: true)]
    private ?string $phone = null;


    #[Assert\Url]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $website = null;


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
        $this->name = $name;

        return $this;
    }


    public function getAddress(): ?string
    {
        return $this->address;
    }


    public function setAddress(?string $address): static
    {
        $this->address = $address;

        return $this;
    }


    public function getCity(): ?string
    {
        return $this->city;
    }


    public function setCity(?string $city): static
    {
        $this->city = $city;

        return $this;
    }


    public function getPostalCode(): ?string
    {
        return $this->postalCode;
    }


    public function setPostalCode(?string $postalCode): static
    {
        $this->postalCode = $postalCode;

        return $this;
    }


    public function getCountry(): ?string
    {
        return $this->country;
    }


    public function setCountry(?string $country): static
    {
        $this->country = $country;

        return $this;
    }


    public function getEmail(): ?string
    {
        return $this->email;
    }


    public function setEmail(?string $email): static
    {
        $this->email = $email;

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


    public function getWebsite(): ?string
    {
        return $this->website;
    }


    public function setWebsite(?string $website): static
    {
        $this->website = $website;

        return $this;
    }
}

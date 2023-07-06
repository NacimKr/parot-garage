<?php

namespace App\Entity;

use App\Repository\ServicesRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ServicesRepository::class)]
class Services
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(
        min: 2,
        max: 50,
        minMessage: 'Le service doit comporter au minimum 2 caractères',
        maxMessage: 'Le service doit comporter au maximum 50 caractères',
    )]
    private ?string $nom = null;

    #[ORM\Column]
    #[Assert\NotBlank]
    #[Assert\GreaterThanOrEqual(100, message:"Le prix doit être au délà de 100€")]
    #[Assert\LessThanOrEqual(1000,  message:"Le prix doit être en-dessous de 1000€")]
    private ?float $prix = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank]
    #[Assert\Length(
        min: 10,
        max: 100,
        minMessage: 'Le service doit comporter au minimum 10 caractères',
        maxMessage: 'Le service doit comporter au maximum 100 caractères',
    )]
    private ?string $description = null;

    // #[ORM\ManyToOne(inversedBy: 'services')]
    // private ?Promotion $promotion = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    // public function getPromotion(): ?Promotion
    // {
    //     return $this->promotion;
    // }

    // public function setPromotion(?Promotion $promotion): static
    // {
    //     $this->promotion = $promotion;

    //     return $this;
    // }
}

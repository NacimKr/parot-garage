<?php

namespace App\Entity;

use App\Repository\HoursRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HoursRepository::class)]
class Hours
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?string $matin_open = null;

    #[ORM\Column]
    private ?string $matin_close = null;

    #[ORM\Column]
    private ?string $aprem_open = null;

    #[ORM\Column]
    private ?string $aprem_close = null;

    #[ORM\OneToOne(inversedBy: 'hours', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Week $days = null;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getMatinOpen(): ?string
    {
        return $this->matin_open;
    }

    public function setMatinOpen(string $matin_open): static
    {
        $this->matin_open = $matin_open;

        return $this;
    }

    public function getMatinClose(): ?string
    {
        return $this->matin_close;
    }

    public function setMatinClose(string $matin_close): static
    {
        $this->matin_close = $matin_close;

        return $this;
    }

    public function getApremOpen(): ?string
    {
        return $this->aprem_open;
    }

    public function setApremOpen(string $aprem_open): static
    {
        $this->aprem_open = $aprem_open;

        return $this;
    }

    public function getApremClose(): ?string
    {
        return $this->aprem_close;
    }

    public function setApremClose(string $aprem_close): static
    {
        $this->aprem_close = $aprem_close;

        return $this;
    }

    public function getDays(): ?Week
    {
        return $this->days;
    }

    public function setDays(Week $days): static
    {
        $this->days = $days;

        return $this;
    }


    public function __toString()
    {
        return $this->getDays();
    }
}

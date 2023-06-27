<?php

namespace App\Entity;

use App\Repository\WeekRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: WeekRepository::class)]
class Week
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\OneToOne(mappedBy: 'days')]
    private ?Hours $hours = null;

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
        $this->name = $name;

        return $this;
    }

    public function getHours(): ?Hours
    {
        return $this->hours;
    }

    public function setHours(Hours $hours): static
    {
        // set the owning side of the relation if necessary
        if ($hours->getDays() !== $this) {
            $hours->setDays($this);
        }

        $this->hours = $hours;

        return $this;
    }


    public function __toString()
    {
        return $this->getHours();
    }
}

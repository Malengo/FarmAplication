<?php

namespace App\Entity;

use App\Repository\CowRepository;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CowRepository::class)]
class Cow
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $weight = null;

    #[ORM\Column]
    private ?float $milkAmount = null;

    #[ORM\Column]
    private ?float $foodAmount = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\LessThan(value: new DateTimeImmutable(), message:"O nascimento não pode ser uma data Futura")]
    private ?\DateTimeInterface $born = null;

    #[ORM\Column]
    private ?bool $isAlive = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getWeight(): ?int
    {
        return $this->weight;
    }

    public function setWeight(int $weight): self
    {
        $this->weight = $weight;

        return $this;
    }

    public function getMilkAmount(): ?float
    {
        return $this->milkAmount;
    }

    public function setMilkAmount(float $milkAmount): self
    {
        $this->milkAmount = $milkAmount;

        return $this;
    }

    public function getFoodAmount(): ?float
    {
        return $this->foodAmount;
    }

    public function setFoodAmount(float $foodAmount): self
    {
        $this->foodAmount = $foodAmount;

        return $this;
    }

    public function getBorn(): ?\DateTimeInterface
    {
        return $this->born;
    }

    public function setBorn(\DateTimeInterface $born): self
    {
        $this->born = $born;

        return $this;
    }

    public function isIsAlive(): ?bool
    {
        return $this->isAlive;
    }

    public function setIsAlive(bool $isAlive): self
    {
        $this->isAlive = $isAlive;

        return $this;
    }
}

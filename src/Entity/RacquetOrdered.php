<?php

namespace App\Entity;

use App\Entity\Order;
use App\Entity\Racquet;
use App\Repository\RacquetOrderedRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=RacquetOrderedRepository::class)
 */
class RacquetOrdered
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Racquet::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $racquet;

    /**
     * @ORM\Column(type="smallint")
     * @Assert\NotBlank()
     * @Assert\GreaterThanOrEqual(1)
     */
    private $quantity;

    /**
     * @ORM\ManyToOne(targetEntity=Order::class, inversedBy="racquets")
     * @ORM\JoinColumn(nullable=false)
     */
    private $orderRef;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRacquet(): ?Racquet
    {
        return $this->racquet;
    }

    public function setRacquet(?Racquet $racquet): self
    {
        $this->racquet = $racquet;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getOrderRef(): ?Order
    {
        return $this->orderRef;
    }

    public function setOrderRef(?Order $orderRef): self
    {
        $this->orderRef = $orderRef;

        return $this;
    }

    public function equals(RacquetOrdered $racquet): bool
    {
        return $this->getRacquet()->getId() === $racquet->getRacquet()->getId();
    }

    public function getTotal(): float
    {
        return $this->getRacquet()->getPrice() * $this->getQuantity();
    }
}

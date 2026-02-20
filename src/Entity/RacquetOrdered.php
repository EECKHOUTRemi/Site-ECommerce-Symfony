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
     * @ORM\Column(type="smallint", length=3)
     */
    private $head_size;

    /**
     * @ORM\Column(type="string", length=5)
     */
    private $string_pattern;

    /**
     * @ORM\Column(type="smallint", length=3)
     */
    private $weight;

    /**
     * @ORM\Column(type="smallint", length=1)
     */
    private $grip_size;

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

    public function getHeadSize(): ?int
    {
        return $this->head_size;
    }

    public function setHeadSize(?int $head_size): self
    {
        $this->head_size = $head_size;

        return $this;
    }

    public function getStringPattern(): ?string
    {
        return $this->string_pattern;
    }

    public function setStringPattern(?string $string_pattern): self
    {
        $this->string_pattern = $string_pattern;

        return $this;
    }

    public function getWeight(): ?int
    {
        return $this->weight;
    }

    public function setWeight(?int $weight): self
    {
        $this->weight = $weight;

        return $this;
    }

    public function getGripSize(): ?int
    {
        return $this->grip_size;
    }

    public function setGripSize(?int $grip_size): self
    {
        $this->grip_size = $grip_size;

        return $this;
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

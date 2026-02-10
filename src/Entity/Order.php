<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=OrderRepository::class)
 * @ORM\Table(name="`order`")
 */
class Order
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity=RacquetOrdered::class, mappedBy="orderRef", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $racquets;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $status = self::STATUS_CART;

    const STATUS_CART = 'cart';
    const STATUS_PENDING = 'pending';
    const STATUS_CONFIRMED = 'confirmed';
    const STATUS_SHIPPED = 'shipped';
    const STATUS_DELIVERED = 'delivered';
    const STATUS_CANCELLED = 'cancelled';

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    public function __construct()
    {
        $this->racquets = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, RacquetOrdered>
     */
    public function getRacquets(): Collection
    {
        return $this->racquets;
    }

    public function addRacquet(RacquetOrdered $racquet): self
    {
        foreach ($this->getRacquets() as $existingRacquet) {
            if ($existingRacquet->equals($racquet)) {
                $existingRacquet->setQuantity(
                    $existingRacquet->getQuantity() + $racquet->getQuantity()
                );
                return $this;
            }
        }

        $this->racquets[] = $racquet;
        $racquet->setOrderRef($this);

        return $this;
    }

    public function removeRacquet(RacquetOrdered $racquet): self
    {
        if ($this->racquets->removeElement($racquet)) {
            // set the owning side to null (unless already changed)
            if ($racquet->getOrderRef() === $this) {
                $racquet->setOrderRef(null);
            }
        }

        return $this;
    }

    public function removeRacquets(): self
    {
        foreach ($this->getRacquets() as $racquet) {
            $this->removeRacquet($racquet);
        }

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getTotal(): float
    {
        $total = 0;
        foreach ($this->getRacquets() as $racquet) {
            $total += $racquet->getTotal();
        }
        return $total;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}

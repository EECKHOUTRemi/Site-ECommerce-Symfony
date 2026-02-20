<?php

namespace App\Entity;

use App\Repository\RacquetRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=RacquetRepository::class)
 */
class Racquet
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $brand;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $model;

    /**
     * @ORM\Column(type="float")
     */
    private $price;

    /**
     * @ORM\Column(type="smallint")
     */
    private $quantity;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $imgExtension;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     * @Assert\Range(min=0, max=10)
     */
    private $avgRating;

    /**
     * @ORM\OneToMany(targetEntity=RacquetRating::class, mappedBy="racquet", orphanRemoval=true)
     */
    private $racquetRating;

    public function __construct()
    {
        $this->racquetRating = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBrand(): ?string
    {
        return $this->brand;
    }

    public function setBrand(string $brand): self
    {
        $this->brand = $brand;

        return $this;
    }

    public function getModel(): ?string
    {
        return $this->model;
    }

    public function setModel(string $model): self
    {
        $this->model = $model;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

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

    public function getImgExtension(): string
    {
        return $this->imgExtension;
    }

    public function setImgExtension(string $imgExtension): self
    {
        $this->imgExtension = $imgExtension;

        return $this;
    }

    public function getAvgRating(): ?int
    {
        return $this->avgRating;
    }

    public function setAvgRating(?int $avgRating): self
    {
        if ($avgRating !== null && $avgRating > 10) {
            $avgRating = 10;
        }
        $this->avgRating = $avgRating;

        return $this;
    }

    /**
     * @return Collection<int, RacquetRating>
     */
    public function getRacquetRatings(): Collection
    {
        return $this->racquetRating;
    }

    public function addRacquetRating(RacquetRating $racquetRating): self
    {
        if (!$this->racquetRating->contains($racquetRating)) {
            $this->racquetRating[] = $racquetRating;
            $racquetRating->setRacquet($this);
        }

        return $this;
    }

    public function removeRacquetRating(RacquetRating $racquetRating): self
    {
        if ($this->racquetRating->removeElement($racquetRating)) {
            // set the owning side to null (unless already changed)
            if ($racquetRating->getRacquet() === $this) {
                $racquetRating->setRacquet(null);
            }
        }

        return $this;
    }
}

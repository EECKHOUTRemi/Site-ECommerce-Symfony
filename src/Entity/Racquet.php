<?php

namespace App\Entity;

use App\Repository\RacquetRepository;
use Doctrine\ORM\Mapping as ORM;

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
     * @ORM\Column(type="integer", nullable=true)
     */
    private $head_size;

    /**
     * @ORM\Column(type="string", length=5, nullable=true)
     */
    private $string_pattern;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $weight;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $grip_size;

    /**
     * @ORM\Column(type="smallint")
     */
    private $price;

    /**
     * @ORM\Column(type="smallint")
     */
    private $quantity;

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

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
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
}

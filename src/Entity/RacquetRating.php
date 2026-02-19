<?php

namespace App\Entity;

use App\Repository\RacquetRatingRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RacquetRatingRepository::class)
 */
class RacquetRating
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="smallint")
     */
    private $rating;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false, name="user_id")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=Racquet::class, inversedBy="racquetRating")
     * @ORM\JoinColumn(nullable=false, name="racquet_id")
     */
    private $racquet;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRating(): ?int
    {
        return $this->rating;
    }

    public function setRating(int $rating): self
    {
        $this->rating = $rating;

        return $this;
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

    public function getRacquet(): ?Racquet
    {
        return $this->racquet;
    }

    public function setRacquet(?Racquet $racquet): self
    {
        $this->racquet = $racquet;

        return $this;
    }
}

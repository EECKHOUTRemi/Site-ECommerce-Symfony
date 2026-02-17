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
    private $userId;

    /**
     * @ORM\ManyToOne(targetEntity=Racquet::class, inversedBy="racquetRating")
     * @ORM\JoinColumn(nullable=false, name="racquet_id")
     */
    private $racquetId;


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

    public function getUserId(): ?User
    {
        return $this->userId;
    }

    public function setUserId(?User $userId): self
    {
        $this->userId = $userId;

        return $this;
    }

    public function getRacquetId(): ?Racquet
    {
        return $this->racquetId;
    }

    public function setRacquetId(?Racquet $racquetId): self
    {
        $this->racquetId = $racquetId;

        return $this;
    }
}

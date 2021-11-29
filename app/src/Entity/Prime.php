<?php

namespace App\Entity;

use App\Repository\PrimeRepository;
use Doctrine\ORM\Mapping as ORM;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema()
 * @ORM\Entity(repositoryClass=PrimeRepository::class)
 */
class Prime
{
    /**
     * @OA\Property(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @OA\Property(type="integer")
     * @ORM\Column(type="integer")
     */
    private $number;

    /**
     *@OA\Property(type="boolean")
     * @ORM\Column(type="boolean")
     */
    private $prime;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumber(): ?int
    {
        return $this->number;
    }

    public function setNumber(int $number): self
    {
        $this->number = $number;

        return $this;
    }

    public function isPrime(): ?bool
    {
        return $this->prime;
    }

    public function setPrime(bool $prime): self
    {
        $this->prime = $prime;

        return $this;
    }
}

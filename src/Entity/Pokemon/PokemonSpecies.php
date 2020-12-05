<?php

namespace App\Entity\Pokemon;

use App\Repository\PokemonSpeciesRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PokemonSpeciesRepository::class)
 */
class PokemonSpecies
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $baseHappiness;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBaseHappiness(): ?int
    {
        return $this->baseHappiness;
    }

    public function setBaseHappiness(int $baseHappiness): self
    {
        $this->baseHappiness = $baseHappiness;

        return $this;
    }
}

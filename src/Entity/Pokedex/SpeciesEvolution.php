<?php

namespace App\Entity\Pokedex;

use App\Repository\Pokedex\SpeciesEvolutionRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SpeciesEvolutionRepository::class)
 */
class SpeciesEvolution
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    public function getId(): ?int
    {
        return $this->id;
    }
}

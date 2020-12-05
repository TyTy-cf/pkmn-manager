<?php

namespace App\Entity\Pokedex;

use App\Repository\Pokedex\EvolutionDetailRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EvolutionDetailRepository::class)
 */
class EvolutionDetail
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

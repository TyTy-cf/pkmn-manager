<?php

namespace App\Entity\Pokedex;

use App\Repository\Pokedex\EvolutionChainRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EvolutionChainRepository::class)
 */
class EvolutionChain
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

<?php

namespace App\Entity\Pokedex;

use App\Entity\Traits\TraitNomenclature;
use App\Repository\Pokedex\EvolutionTriggerRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EvolutionTriggerRepository::class)
 */
class EvolutionTrigger
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    use TraitNomenclature;

    public function getId(): ?int
    {
        return $this->id;
    }
}

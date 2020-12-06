<?php

namespace App\Entity\Pokedex;

use App\Entity\Traits\TraitNomenclature;
use App\Repository\Pokedex\EggGroupRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EggGroupRepository::class)
 */
class EggGroup
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    use TraitNomenclature;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Pokemon\PokemonSpecies", mappedBy="eggGroup")
     */
    private Collection $pokemonSpecies;

    public function getId(): ?int
    {
        return $this->id;
    }
}

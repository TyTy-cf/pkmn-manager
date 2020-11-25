<?php

namespace App\Entity\Game;

use App\Entity\Moves\PokemonMovesLevel;
use App\Entity\Traits\TraitLanguage;
use App\Entity\Traits\TraitNames;
use App\Entity\Traits\TraitSlug;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Game\GameVersionRepository")
 */
class GameVersion
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    use TraitNames;

    use TraitSlug;

    use TraitLanguage;

    /**
     * @ORM\Column(type="string", length=2)
     */
    private string $code;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Moves\PokemonMovesLevel", mappedBy="gameInfos")
     */
    private Collection $pokemonMovesLevels;

    public function __construct()
    {
        $this->pokemonMovesLevels = new ArrayCollection();
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getCode(): ?string
    {
        return $this->code;
    }

    /**
     * @param string $code
     * @return $this
     */
    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    /**
     * @return Collection|PokemonMovesLevel[]
     */
    public function getPokemonMovesLevels(): Collection
    {
        return $this->pokemonMovesLevels;
    }

    public function addPokemonMovesLevel(PokemonMovesLevel $pokemonMovesLevel): void
    {
        if (!$this->pokemonMovesLevels->contains($pokemonMovesLevel)) {
            $this->pokemonMovesLevels[] = $pokemonMovesLevel;
            $pokemonMovesLevel->setGameinfos($this);
        }
    }

    public function removePokemonMovesLevel(PokemonMovesLevel $pokemonMovesLevel): void
    {
        if ($this->pokemonMovesLevels->removeElement($pokemonMovesLevel)) {
            // set the owning side to null (unless already changed)
            if ($pokemonMovesLevel->getGameinfos() === $this) {
                $pokemonMovesLevel->setGameinfos(null);
            }
        }
    }
}

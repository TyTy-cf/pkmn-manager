<?php

namespace App\Entity\Game;

use App\Entity\Moves\PokemonMovesLevel;
use App\Entity\Traits\TraitNames;
use App\Repository\Game\GameInfosRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=GameInfosRepository::class)
 */
class GameInfos
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    use TraitNames;

    /**
     * @ORM\Column(type="string", length=6)
     */
    private ?string $code;

    /**
     * @ORM\Column(type="string", length=2, nullable=true)
     */
    private ?string $codeVersion;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private ?int $nbPkmn;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Moves\PokemonMovesLevel", mappedBy="gameInfos")
     */
    private Collection $pokemonMovesLevels;

    public function __construct()
    {
        $this->pokemonMovesLevels = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getNbPkmn(): ?int
    {
        return $this->nbPkmn;
    }

    public function setNbPkmn(?int $nbPkmn): self
    {
        $this->nbPkmn = $nbPkmn;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getCodeVersion(): ?string
    {
        return $this->codeVersion;
    }

    /**
     * @param string|null $codeVersion
     */
    public function setCodeVersion(?string $codeVersion): void
    {
        $this->codeVersion = $codeVersion;
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

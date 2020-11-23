<?php


namespace App\Entity\Moves;

use App\Entity\GameInfos;
use App\Entity\Pokemon\Pokemon;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\JoinColumn;
use phpDocumentor\Reflection\Types\Integer;

/**
 * Class PokemonMovesLevel
 * @package App\Entity\Moves
 * @Entity
 * @ORM\Table(name="pokemon_moves_level")
 */
class PokemonMovesLevel
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private string $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Pokemon\Pokemon", inversedBy="pokemonMovesLevel", fetch="EAGER")
     * @JoinColumn(name="pokemon_id", referencedColumnName="id")
     */
    private Pokemon $pokemon;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Moves\Moves", inversedBy="pokemonMovesLevel")
     * @JoinColumn(name="move_id", referencedColumnName="id")
     */
    private Moves $move;

    /**
     * @var int $level
     * @ORM\Column(name="level", type="integer", length=3)
     */
    private Integer $level;

    /**
     * @ORM\ManyToOne(targetEntity=GameInfos::class, inversedBy="pokemonMovesLevels")
     */
    private $gameinfos;


    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     */
    public function setId(string $id): void
    {
        $this->id = $id;
    }

    /**
     * @return Pokemon
     */
    public function getPokemon(): Pokemon
    {
        return $this->pokemon;
    }

    /**
     * @param Pokemon $pokemon
     */
    public function setPokemon(Pokemon $pokemon): void
    {
        $this->pokemon = $pokemon;
    }

    /**
     * @return Moves
     */
    public function getMove(): Moves
    {
        return $this->move;
    }

    /**
     * @param Moves $move
     */
    public function setMove(Moves $move): void
    {
        $this->move = $move;
    }

    /**
     * @return int
     */
    public function getLevel(): int
    {
        return $this->level;
    }

    /**
     * @param int $level
     */
    public function setLevel(int $level): void
    {
        $this->level = $level;
    }

    public function getGameinfos(): ?GameInfos
    {
        return $this->gameinfos;
    }

    public function setGameinfos(?GameInfos $gameinfos): self
    {
        $this->gameinfos = $gameinfos;

        return $this;
    }

}
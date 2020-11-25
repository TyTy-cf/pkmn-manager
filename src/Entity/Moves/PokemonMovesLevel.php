<?php


namespace App\Entity\Moves;

use App\Entity\Game\GameVersion;
use App\Entity\Pokemon\Pokemon;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\JoinColumn;

/**
 * Class PokemonMovesLevel
 * @package App\Entity\Move
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
     * @ORM\ManyToOne(targetEntity="App\Entity\Moves\Move", inversedBy="pokemonMovesLevel")
     * @JoinColumn(name="move_id", referencedColumnName="id")
     */
    private Move $move;

    /**
     * @var int $level
     * @ORM\Column(name="level", type="integer", length=3)
     */
    private int $level;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Game\GameVersion", inversedBy="pokemonMovesLevels")
     * @JoinColumn(name="gameinfos_id", referencedColumnName="id")
     */
    private ?GameVersion $gameInfos;

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
     * @return Move
     */
    public function getMove(): Move
    {
        return $this->move;
    }

    /**
     * @param Move $move
     */
    public function setMove(Move $move): void
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

    /**
     * @return GameVersion|null
     */
    public function getGameinfos(): ?GameVersion
    {
        return $this->gameInfos;
    }

    /**
     * @param GameVersion|null $gameInfos
     */
    public function setGameinfos(?GameVersion $gameInfos): void
    {
        $this->gameInfos = $gameInfos;
    }

}
<?php


namespace App\Entity\Moves;

use App\Entity\Game\GameVersion;
use App\Entity\Pokemon\Pokemon;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\JoinColumn;

/**
 * Class PokemonMovesLearnVersion
 * @package App\Entity\Move
 * @Entity
 * @ORM\Table(name="pokemon_moves_learn_version")
 */
class PokemonMovesLearnVersion
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private string $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Pokemon\Pokemon", inversedBy="pokemonMovesLearnVersion", fetch="EAGER")
     * @JoinColumn(name="pokemon_id", referencedColumnName="id")
     */
    private Pokemon $pokemon;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Moves\Move", inversedBy="pokemonMovesLearnVersion")
     * @JoinColumn(name="move_id", referencedColumnName="id")
     */
    private Move $move;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Moves\MoveLearnMethod")
     * @JoinColumn(name="move_learn_method_id", referencedColumnName="id")
     */
    private MoveLearnMethod $moveLearnMethod;

    /**
     * @var int $level
     * @ORM\Column(name="level", type="integer", length=3)
     */
    private int $level;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Game\GameVersion")
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
     * @return MoveLearnMethod
     */
    public function getMoveLearnMethod(): MoveLearnMethod
    {
        return $this->moveLearnMethod;
    }

    /**
     * @param MoveLearnMethod $moveLearnMethod
     */
    public function setMoveLearnMethod(MoveLearnMethod $moveLearnMethod): void
    {
        $this->moveLearnMethod = $moveLearnMethod;
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
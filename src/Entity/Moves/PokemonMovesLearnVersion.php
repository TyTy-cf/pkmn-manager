<?php


namespace App\Entity\Moves;

use App\Entity\Traits\TraitSlug;
use App\Entity\Versions\VersionGroup;
use App\Entity\Pokemon\Pokemon;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\JoinColumn;

/**
 * Class PokemonMovesLearnVersion
 * @package App\Entity\Move
 * @Entity
 * @ORM\Table(
 *     name="pokemon_moves_learn_version",
 *     indexes={@ORM\Index(
 *          name="slug_idx",
 *          columns={"slug"}
 *     )}
 * )
 */
class PokemonMovesLearnVersion
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private string $id;

    use TraitSlug;

    /**
     * @var int $level
     * @ORM\Column(name="level", type="integer", length=3)
     */
    private int $level;

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
     * @ORM\ManyToOne(targetEntity="App\Entity\Versions\VersionGroup")
     * @JoinColumn(name="version_group_id", referencedColumnName="id")
     */
    private VersionGroup $versionGroup;

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
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
     * @return PokemonMovesLearnVersion
     */
    public function setLevel(int $level): self
    {
        $this->level = $level;
        return $this;
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
     * @return PokemonMovesLearnVersion
     */
    public function setPokemon(Pokemon $pokemon): self
    {
        $this->pokemon = $pokemon;
        return $this;
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
     * @return PokemonMovesLearnVersion
     */
    public function setMove(Move $move): self
    {
        $this->move = $move;
        return $this;
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
     * @return PokemonMovesLearnVersion
     */
    public function setMoveLearnMethod(MoveLearnMethod $moveLearnMethod): self
    {
        $this->moveLearnMethod = $moveLearnMethod;
        return $this;
    }

    /**
     * @return VersionGroup
     */
    public function getVersionGroup(): VersionGroup
    {
        return $this->versionGroup;
    }

    /**
     * @param VersionGroup $versionGroup
     * @return PokemonMovesLearnVersion
     */
    public function setVersionGroup(VersionGroup $versionGroup): self
    {
        $this->versionGroup = $versionGroup;
        return $this;
    }

}
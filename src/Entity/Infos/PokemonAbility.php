<?php


namespace App\Entity\Infos;

use App\Entity\Pokemon\Pokemon;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;

/**
 * Class PokemonAbility
 * @package App\Entity\Infos
 * @ORM\Entity(repositoryClass="App\Repository\Infos\PokemonAbilityRepository")
 *
 */
class PokemonAbility
{
    /**
     * @var int $id l'id du pkmn en bdd
     *
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(name="id", type="integer", length=6)
     */
    private int $id;

    /**
     * @var Pokemon
     * @ORM\ManyToOne(targetEntity="App\Entity\Pokemon\Pokemon", inversedBy="pokemonsAbility")
     * @JoinColumn(name="pokemon_id")
     */
    private Pokemon $pokemon;

    /**
     * @var Ability
     * @ORM\ManyToOne(targetEntity="App\Entity\Infos\Ability", inversedBy="pokemonsAbility")
     * @JoinColumn(name="ability_id")
     */
    private Ability $ability;

    /**
     * @var bool $hidden
     * @ORM\Column(name="is_hidden", type="boolean", nullable=true)
     */
    private bool $hidden;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
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
     * @return PokemonAbility
     */
    public function setPokemon(Pokemon $pokemon): self
    {
        $this->pokemon = $pokemon;
        return $this;
    }

    /**
     * @return Ability
     */
    public function getAbility(): Ability
    {
        return $this->ability;
    }

    /**
     * @param Ability $ability
     * @return PokemonAbility
     */
    public function setAbility(Ability $ability): self
    {
        $this->ability = $ability;
        return $this;
    }

    /**
     * @return bool
     */
    public function isHidden(): bool
    {
        return $this->hidden;
    }

    /**
     * @param bool $hidden
     * @return PokemonAbility
     */
    public function setHidden(bool $hidden): self
    {
        $this->hidden = $hidden;
        return $this;
    }

}

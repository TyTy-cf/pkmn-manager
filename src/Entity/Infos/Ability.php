<?php


namespace App\Entity\Infos;

use App\Entity\Traits\TraitDescription;
use App\Entity\Traits\TraitNomenclature;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OneToMany;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Class Ability
 * @package App\Entity\Infos
 *
 * @ORM\Table(name="ability", indexes={
 *     @ORM\Index(
 *          name="slug_idx",
 *          columns={"slug"}
 *     )
 * })
 * @ORM\Entity(repositoryClass="App\Repository\Infos\AbilityRepository")
 * @UniqueEntity(
 *     fields={"name"},
 *     message="Ce talent existe déjà !"
 * )
 */
class Ability
{
    /**
     * @var int $id id de l'abstract info
     *
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(name="id", type="integer", length=6)
     */
    private int $id;

    /**
     * @OneToMany(targetEntity="App\Entity\Infos\AbilityVersionGroup", mappedBy="ability")
     */
    private Collection $abilityVersionGroup;

    /**
     * @OneToMany(targetEntity="App\Entity\Infos\PokemonAbility", mappedBy="ability", cascade={"persist"})
     */
    private Collection $pokemonsAbility;

    use TraitDescription;

    use TraitNomenclature;

    public function __construct()
    {
        $this->pokemonsAbility = new ArrayCollection();
        $this->abilityVersionGroup = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getPokemonsAbility()
    {
        return $this->pokemonsAbility;
    }

    /**
     * @param $pokemon
     */
    public function addPokemonAbility($pokemon): void
    {
        if (!$this->pokemonsAbility->contains($pokemon)) {
            $this->pokemonsAbility->add($pokemon);
        }
    }

    /**
     * @param $pokemon
     */
    public function removePokemonAbility($pokemon): void
    {
        if ($this->pokemonsAbility->contains($pokemon)) {
            $this->pokemonsAbility->remove($pokemon);
        }
    }

    /**
     * @return mixed
     */
    public function getAbilityVersionGroup(): Collection
    {
        return $this->abilityVersionGroup;
    }

    /**
     * @param $ability
     */
    public function addAbilityVersionGroup($ability): void
    {
        if (!$this->abilityVersionGroup->contains($ability)) {
            $this->abilityVersionGroup->add($ability);
        }
    }

    /**
     * @param $ability
     */
    public function removeAbilityVersionGroup($ability): void
    {
        if ($this->abilityVersionGroup->contains($ability)) {
            $this->abilityVersionGroup->remove($ability);
        }
    }

}

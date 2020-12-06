<?php


namespace App\Entity\Infos;

use App\Entity\Traits\TraitDescription;
use App\Entity\Traits\TraitNomenclature;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
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
     * @ORM\ManyToMany(targetEntity="App\Entity\Pokemon\Pokemon", mappedBy="abilities")
     */
    private Collection $pokemons;

    use TraitDescription;

    use TraitNomenclature;

    public function __construct()
    {
        $this->pokemons = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getPokemons()
    {
        return $this->pokemons;
    }

    /**
     * @param $pokemon
     */
    public function addPokemon($pokemon): void
    {
        if (!$this->pokemons->contains($pokemon)) {
            $this->pokemons->add($pokemon);
        }
    }

    /**
     * @param $pokemon
     */
    public function removePokemon($pokemon): void
    {
        if ($this->pokemons->contains($pokemon)) {
            $this->pokemons->remove($pokemon);
        }
    }

}
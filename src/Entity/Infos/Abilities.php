<?php


namespace App\Entity\Infos;

use App\Entity\Pokemon\Pokemon;
use App\Entity\Traits\TraitDescription;
use App\Entity\Traits\TraitLanguage;
use App\Entity\Traits\TraitNames;
use App\Entity\Traits\TraitSlug;
use App\Entity\Users\Language;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Class Abilities
 * @package App\Entity\Infos
 *
 * @ORM\Table(name="abilities")
 * @ORM\Entity(repositoryClass="App\Repository\Infos\AbilitiesRepository")
 * @UniqueEntity(
 *     fields={"name"},
 *     message="Ce talent existe déjà !"
 * )
 */
class Abilities
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

    use TraitNames;

    use TraitSlug;

    use TraitDescription;

    use TraitLanguage;

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
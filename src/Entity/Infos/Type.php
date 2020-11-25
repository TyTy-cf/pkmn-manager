<?php


namespace App\Entity\Infos;


use App\Entity\Pokemon\Pokemon;
use App\Entity\Traits\TraitLanguage;
use App\Entity\Traits\TraitNames;
use App\Entity\Traits\TraitSlug;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Class Type *
 * @package App\Entity\Infos
 *
 * @ORM\Table(name="type")
 * @ORM\Entity(repositoryClass="App\Repository\Infos\TypeRepository")
 * @UniqueEntity(
 *     fields={"name"},
 *     message="Ce type existe déjà !"
 * )
 */
class Type
{
    /**
     * @var int $id id de l'abstract info
     *
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(name="id", type="integer", length=6)
     */
    private $id;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Pokemon\Pokemon", mappedBy="types")
     */
    private $pokemons;

    /**
     * @ORM\Column(name="img", type="string", length=255, nullable=true)
     */
    private ?string $img;

    public function __construct()
    {
        $this->pokemons = new ArrayCollection();
    }

    use TraitNames;

    use TraitSlug;

    use TraitLanguage;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
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

    /**
     * @return string|null
     */
    public function getImg(): ?string
    {
        return $this->img;
    }

    /**
     * @param string|null $img
     */
    public function setImg(?string $img): void
    {
        $this->img = $img;
    }

}
<?php


namespace App\Entity\Infos;


use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\ManyToMany;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Class Type *
 * @package App\Entity\Infos
 *
 * @ORM\Table(name="type")
 * @ORM\Entity(repositoryClass="App\Repository\Infos\TypeRepository")
 * @UniqueEntity(
 *     fields={"nameEn"},
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

    use TraitNames;

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
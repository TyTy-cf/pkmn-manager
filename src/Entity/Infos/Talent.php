<?php


namespace App\Entity\Infos;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\ManyToMany;

/**
 * Class Talent *
 * @package App\Entity\Infos
 *
 * @ORM\Table(name="talent")
 * @Entity
 */
class Talent
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
     * @var string $nom nom de l'abstract info
     *
     * @ORM\Column(name="nom", type="string", length=48)
     */
    private $nom;

    /**
     * @var string $description la description du talent
     *
     * @ORM\Column(name="description", type="string", length=255)
     */
    private $description;

    /**
     * @ManyToMany(targetEntity="App\Entity\Pokemon\Pokemon", mappedBy="talents")
     */
    private $pokemons;

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
     * @return string
     */
    public function getNom(): string
    {
        return $this->nom;
    }

    /**
     * @param string $nom
     */
    public function setNom(string $nom): void
    {
        $this->nom = $nom;
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
     * @param mixed $pokemons
     */
    public function setPokemons($pokemons): void
    {
        $this->pokemons = $pokemons;
    }

}
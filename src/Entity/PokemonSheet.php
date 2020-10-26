<?php


namespace App\Entity;


use App\Entity\Infos\Genre;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\OneToOne;

/**
 * Class PokemonSheet
 * @package App\Entity
 * @Entity
 */
class PokemonSheet
{
    /**
     * @var string $id id de la sheet pokemon
     *
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(name="id", type="string", length=6)
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="pokemon")
     * @ORM\JoinColumn(name="nom_pokemon_id", referencedColumnName="id")
     */
    private $nomPokemon;

    /**
     * @var string|null $surnom le surnom du pokemon
     *
     * @ORM\Column(name="surnom", type="string", length=20, nullable=true)
     */
    private $surnom;

    /**
     * @var Genre $surnom le genre du pokemon
     *
     * @OneToOne(targetEntity="App\Entity\Infos\Genre")
     * @JoinColumn(name="genre_id", referencedColumnName="id")
     */
    private $genre;

    /**
     * @var integer $niveau le niveau du pokemon
     *
     * @ORM\Column(name="niveau", type="integer", length=3)
     */
    private $niveau;

    /**
     * @OneToOne(targetEntity="App\Entity\Infos\Nature")
     * @JoinColumn(name="nature_id", referencedColumnName="id")
     */
    private $nature;

    /**
     * @OneToOne(targetEntity="App\Entity\Stats\IvsPkmn")
     * @JoinColumn(name="ivs_id", referencedColumnName="id")
     */
    private $ivs;

    /**
     * @OneToOne(targetEntity="App\Entity\Stats\EvsPkmn")
     * @JoinColumn(name="evs_id", referencedColumnName="id")
     */
    private $evs;

    /**
     * @OneToOne(targetEntity="App\Entity\Stats\StatsPkmn")
     * @JoinColumn(name="stats_id", referencedColumnName="id")
     */
    private $stats;

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
     * @return mixed
     */
    public function getNomPokemon()
    {
        return $this->nomPokemon;
    }

    /**
     * @param mixed $nomPokemon
     */
    public function setNomPokemon($nomPokemon): void
    {
        $this->nomPokemon = $nomPokemon;
    }

    /**
     * @return string|null
     */
    public function getSurnom(): ?string
    {
        return $this->surnom;
    }

    /**
     * @param string|null $surnom
     */
    public function setSurnom(?string $surnom): void
    {
        $this->surnom = $surnom;
    }

    /**
     * @return Genre
     */
    public function getGenre(): Genre
    {
        return $this->genre;
    }

    /**
     * @param Genre $genre
     */
    public function setGenre(Genre $genre): void
    {
        $this->genre = $genre;
    }

    /**
     * @return int
     */
    public function getNiveau(): int
    {
        return $this->niveau;
    }

    /**
     * @param int $niveau
     */
    public function setNiveau(int $niveau): void
    {
        $this->niveau = $niveau;
    }

    /**
     * @return mixed
     */
    public function getNature()
    {
        return $this->nature;
    }

    /**
     * @param mixed $nature
     */
    public function setNature($nature): void
    {
        $this->nature = $nature;
    }

    /**
     * @return mixed
     */
    public function getIvs()
    {
        return $this->ivs;
    }

    /**
     * @param mixed $ivs
     */
    public function setIvs($ivs): void
    {
        $this->ivs = $ivs;
    }

    /**
     * @return mixed
     */
    public function getEvs()
    {
        return $this->evs;
    }

    /**
     * @param mixed $evs
     */
    public function setEvs($evs): void
    {
        $this->evs = $evs;
    }

    /**
     * @return mixed
     */
    public function getStats()
    {
        return $this->stats;
    }

    /**
     * @param mixed $stats
     */
    public function setStats($stats): void
    {
        $this->stats = $stats;
    }

}
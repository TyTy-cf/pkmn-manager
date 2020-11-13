<?php


namespace App\Entity\Pokemon;


use App\Entity\Infos\Gender;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\OneToOne;

/**
 * Class PokemonSheet
 * @package App\Entity
 *
 * @ORM\Table(name="pokemon_sheet")
 * @Entity
 */
class PokemonSheet
{
    /**
     * @var int $id id de la sheet pokemon
     *
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(name="id", type="integer", length=6)
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="pokemon")
     * @ORM\JoinColumn(name="name_pokemon", referencedColumnName="id")
     */
    private $namePokemon;

    /**
     * @var string|null $nickname le surnom du pokemon
     *
     * @ORM\Column(name="nickname", type="string", length=20, nullable=true)
     */
    private $nickname;

    /**
     * @var Gender $gender le genre du pokemon
     *
     * @OneToOne(targetEntity="App\Entity\Infos\Gender")
     * @JoinColumn(name="gender_id", referencedColumnName="id")
     */
    private $gender;

    /**
     * @var integer $level le niveau du pokemon
     *
     * @ORM\Column(name="level", type="integer", length=3)
     */
    private $level;

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
    public function getNamePokemon()
    {
        return $this->namePokemon;
    }

    /**
     * @param mixed $namePokemon
     */
    public function setNamePokemon($namePokemon): void
    {
        $this->namePokemon = $namePokemon;
    }

    /**
     * @return string|null
     */
    public function getNickname(): ?string
    {
        return $this->nickname;
    }

    /**
     * @param string|null $nickname
     */
    public function setNickname(?string $nickname): void
    {
        $this->nickname = $nickname;
    }

    /**
     * @return Gender
     */
    public function getGender(): Gender
    {
        return $this->gender;
    }

    /**
     * @param Gender $gender
     */
    public function setGender(Gender $gender): void
    {
        $this->gender = $gender;
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
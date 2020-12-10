<?php


namespace App\Entity\Pokemon;


use App\Entity\Infos\Ability;
use App\Entity\Infos\Gender;
use App\Entity\Infos\Nature;
use App\Entity\Moves\Move;
use App\Entity\Stats\StatsEv;
use App\Entity\Stats\StatsIv;
use App\Entity\Stats\Stats;
use App\Entity\Users\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\ManyToOne;
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
    private int $id;

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
    private ?string $nickname;

    /**
     * @var integer $level le niveau du pokemon
     *
     * @ORM\Column(name="level", type="integer", length=3)
     */
    private int $level;

    /**
     * @var Gender $gender le genre du pokemon
     *
     * @OneToOne(targetEntity="App\Entity\Infos\Gender")
     * @JoinColumn(name="gender_id", referencedColumnName="id")
     */
    private Gender $gender;

    /**
     * @ManyToOne(targetEntity="App\Entity\Infos\Nature")
     * @JoinColumn(name="nature_id", referencedColumnName="id")
     */
    private Nature $nature;

    /**
     * @ManyToOne(targetEntity="App\Entity\Infos\Ability")
     * @JoinColumn(name="ability_id", referencedColumnName="id")
     */
    private Ability $ability;

    /**
     * @OneToOne(targetEntity="App\Entity\Stats\StatsIv")
     * @JoinColumn(name="ivs_id", referencedColumnName="id")
     */
    private StatsIv $ivs;

    /**
     * @OneToOne(targetEntity="App\Entity\Stats\StatsEv")
     * @JoinColumn(name="evs_id", referencedColumnName="id")
     */
    private StatsEv $evs;

    /**
     * @OneToOne(targetEntity="App\Entity\Stats\Stats")
     * @JoinColumn(name="stats_id", referencedColumnName="id")
     */
    private Stats $stats;

    /**
     * @ManyToMany(targetEntity="App\Entity\Moves\Move")
     * @JoinTable(name="pokemon_sheet_moves",
     *      joinColumns={@JoinColumn(name="pokemon_sheet_id", referencedColumnName="id")},
     *      inverseJoinColumns={@JoinColumn(name="move_id", referencedColumnName="id")}
     *      )
     */
    private Collection $moves;

    /**
     * @var User|null
     *
     * @ManyToOne(targetEntity="App\Entity\Users\User", inversedBy="pokemonsSheet")
     * @JoinColumn(name="user_id", referencedColumnName="id", nullable=true)
     */
    private ?User $user;

    /**
     * PokemonSheet constructor.
     */
    public function __construct()
    {
        $this->moves = new ArrayCollection();
    }

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
     * @return Ability
     */
    public function getAbility(): Ability
    {
        return $this->ability;
    }

    /**
     * @param Ability $ability
     */
    public function setAbility(Ability $ability): void
    {
        $this->ability = $ability;
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

    /**
     * @param Move $move
     */
    public function addMove(Move $move): void
    {
        if (!$this->moves->contains($move)) {
            $this->moves->add($move);
        }
    }

    /**
     * @return mixed
     */
    public function getMoves(): Collection
    {
        return $this->moves;
    }

    /**
     * @param Move $move
     */
    public function removeType(Move $move)
    {
        if ($this->moves->contains($move)) {
            $this->moves->removeElement($move);
        }
    }

    public function removeMove(Move $move): self
    {
        $this->moves->removeElement($move);

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

}
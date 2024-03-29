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
     * @var Pokemon
     * @ORM\ManyToOne(targetEntity="App\Entity\Pokemon\Pokemon")
     * @JoinColumn(name="pokemon_id", nullable=true)
     */
    private Pokemon $pokemon;

    /**
     * @var string|null $nickname le surnom du pokemon
     *
     * @ORM\Column(name="nickname", type="string", length=24, nullable=true)
     */
    private ?string $nickname;

    /**
     * @var integer|null $level le niveau du pokemon
     *
     * @ORM\Column(name="level", type="integer", length=3, nullable=true)
     */
    private ?int $level;

    /**
     * @var Gender|null $gender le genre du pokemon
     *
     * @ManyToOne(targetEntity="App\Entity\Infos\Gender")
     * @JoinColumn(name="gender_id", referencedColumnName="id", nullable=true)
     */
    private ?Gender $gender;

    /**
     * @ManyToOne(targetEntity="App\Entity\Infos\Nature")
     * @JoinColumn(name="nature_id", referencedColumnName="id", nullable=true)
     */
    private ?Nature $nature;

    /**
     * @ManyToOne(targetEntity="App\Entity\Infos\Ability")
     * @JoinColumn(name="ability_id", referencedColumnName="id", nullable=true)
     */
    private ?Ability $ability;

    /**
     * @OneToOne(targetEntity="App\Entity\Stats\StatsIv")
     * @JoinColumn(name="ivs_id", referencedColumnName="id", nullable=true)
     */
    private ?StatsIv $ivs;

    /**
     * @OneToOne(targetEntity="App\Entity\Stats\StatsEv")
     * @JoinColumn(name="evs_id", referencedColumnName="id", nullable=true)
     */
    private ?StatsEv $evs;

    /**
     * @OneToOne(targetEntity="App\Entity\Stats\Stats")
     * @JoinColumn(name="stats_id", referencedColumnName="id", nullable=true)
     */
    private ?Stats $stats;

    /**
     * @ManyToMany(targetEntity="App\Entity\Moves\Move")
     * @JoinTable(name="pokemon_sheet_moves",
     *      joinColumns={@JoinColumn(name="pokemon_sheet_id", referencedColumnName="id")},
     *      inverseJoinColumns={@JoinColumn(name="move_id", referencedColumnName="id")}
     * )
     */
    private Collection $moves;

    /**
     * @var string|null $moveSetName
     *
     * @ORM\Column(name="move_set_name", type="string", length=60, nullable=true)
     */
    private ?string $moveSetName;

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
     * @return string
     */
    public function getMoveSetName(): string
    {
        return $this->moveSetName;
    }

    /**
     * @param string $moveSetName
     * @return PokemonSheet
     */
    public function setMoveSetName(string $moveSetName): PokemonSheet
    {
        $this->moveSetName = $moveSetName;
        return $this;
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
     * @return Ability|null
     */
    public function getAbility(): ?Ability
    {
        return $this->ability;
    }

    /**
     * @param Ability|null $ability
     */
    public function setAbility(?Ability $ability): void
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
    public function getIvs(): ?StatsIv
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
     * @return StatsEv
     */
    public function getEvs(): ?StatsEv
    {
        return $this->evs;
    }

    /**
     * @param StatsEv $evs
     */
    public function setEvs(StatsEv $evs): void
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

    /**
     * @return Pokemon
     */
    public function getPokemon(): Pokemon
    {
        return $this->pokemon;
    }

    /**
     * @param Pokemon $pokemon
     * @return PokemonSheet
     */
    public function setPokemon(Pokemon $pokemon): PokemonSheet
    {
        $this->pokemon = $pokemon;
        return $this;
    }

}

<?php


namespace App\Entity\Pokemon;

use App\Entity\Infos\Talent;
use App\Entity\Infos\Type;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\ManyToMany;

/**
 * Class Pokemon
 * @package App\Entity
 *
 * @ORM\Table(name="pokemon")
 * @Entity
 */
class Pokemon
{
    /**
     * @var int $id l'id du pkmn en bdd
     *
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(name="id", type="integer", length=6)
     */
    private $id;

    /**
     * @var string $nom le nom du pokemon
     * @ORM\Column(name="nom", type="string", length=120)
     */
    private $nom;

    /**
     * @var integer $base_pv la base des pv du pokemon
     * @ORM\Column(name="base_hp", type="integer", length=3)
     */
    private $base_pv;

    /**
     * @var integer $base_atk la base d'atk du pokemon
     * @ORM\Column(name="base_atk", type="integer", length=3)
     */
    private $base_atk;

    /**
     * @var integer $base_def la base de def du pokemon
     * @ORM\Column(name="base_def", type="integer", length=3)
     */
    private $base_def;

    /**
     * @var integer $base_spa la base d'atk spé du pokemon
     * @ORM\Column(name="base_spa", type="integer", length=3)
     */
    private $base_spa;

    /**
     * @var integer $base_spd la base de defspe du pokemon
     * @ORM\Column(name="base_spd", type="integer", length=3)
     */
    private $base_spd;

    /**
     * @var integer $base_spe la base de speed du pokemon
     * @ORM\Column(name="base_spe", type="integer", length=3)
     */
    private $base_spe;

    /**
     * @ManyToMany(targetEntity="App\Entity\Infos\Talent", inversedBy="pokemons")
     * @JoinTable(name="pokemon_talents")
     */
    private $talents;

    /**
     * @ManyToMany(targetEntity="App\Entity\Infos\Type", inversedBy="pokemons")
     * @JoinTable(name="pokemon_talents")
     */
    private $types;

    /**
     * Pokemon constructor.
     */
    public function __construct() {
        $this->talents = new ArrayCollection();
        $this->types = new ArrayCollection();
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
     * @return int
     */
    public function getBasePv(): int
    {
        return $this->base_pv;
    }

    /**
     * @param int $base_pv
     */
    public function setBasePv(int $base_pv): void
    {
        $this->base_pv = $base_pv;
    }

    /**
     * @return int
     */
    public function getBaseAtk(): int
    {
        return $this->base_atk;
    }

    /**
     * @param int $base_atk
     */
    public function setBaseAtk(int $base_atk): void
    {
        $this->base_atk = $base_atk;
    }

    /**
     * @return int
     */
    public function getBaseDef(): int
    {
        return $this->base_def;
    }

    /**
     * @param int $base_def
     */
    public function setBaseDef(int $base_def): void
    {
        $this->base_def = $base_def;
    }

    /**
     * @return int
     */
    public function getBaseSpa(): int
    {
        return $this->base_spa;
    }

    /**
     * @param int $base_spa
     */
    public function setBaseSpa(int $base_spa): void
    {
        $this->base_spa = $base_spa;
    }

    /**
     * @return int
     */
    public function getBaseSpd(): int
    {
        return $this->base_spd;
    }

    /**
     * @param int $base_spd
     */
    public function setBaseSpd(int $base_spd): void
    {
        $this->base_spd = $base_spd;
    }

    /**
     * @return int
     */
    public function getBaseSpe(): int
    {
        return $this->base_spe;
    }

    /**
     * @param int $base_spe
     */
    public function setBaseSpe(int $base_spe): void
    {
        $this->base_spe = $base_spe;
    }

    /**
     * @param Talent $talent
     */
    public function addTalent(Talent $talent): void
    {
        if ($this->talents->contains($talent)) {
            $this->talents->add($talent);
        }
    }

    /**
     * @return mixed
     */
    public function getTalents(): Collection
    {
        return $this->talents;
    }

    /**
     * @param Type $type
     */
    public function addType(Type $type): void
    {
        if ($this->types->contains($type)) {
            $this->types->add($type);
        }
    }

    /**
     * @return mixed
     */
    public function getTypes(): Collection
    {
        return $this->types;
    }
}
<?php


namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\ManyToMany;

/**
 * Class Pokemon
 * @package App\Entity
 *
 * @Entity
 */
class Pokemon
{
    /**
     * @var string $id l'id du pkmn en bdd
     *
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(name="id", type="string", length=6)
     */
    private $id;

    /**
     * @var integer $nom le nom du pokemon
     * @ORM\Column(name="nom", type="integer", length=3)
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
     * @ManyToMany(targetEntity="App\Entity\Infos\Talent")
     * @JoinTable(name="pokemon_talents",
     *      joinColumns={@JoinColumn(name="pokemon_id", referencedColumnName="id")},
     *      inverseJoinColumns={@JoinColumn(name="talent_id", referencedColumnName="id")}
     * )
     */
    private $talents;

    /**
     * Pokemon constructor.
     */
    public function __construct() {
        $this->talents = new ArrayCollection();
    }

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
     * @return int
     */
    public function getNom(): int
    {
        return $this->nom;
    }

    /**
     * @param int $nom
     */
    public function setNom(int $nom): void
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
     * @return mixed
     */
    public function getTalents()
    {
        return $this->talents;
    }

    /**
     * @param mixed $talents
     */
    public function setTalents($talents): void
    {
        $this->talents = $talents;
    }
}
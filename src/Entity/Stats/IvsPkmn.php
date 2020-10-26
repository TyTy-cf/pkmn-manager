<?php


namespace App\Entity\Stats;


use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Entity;

/**
 * Class IvsPkmn
 * @package App\Entity\Stats
 *
 * @Entity
 */
class IvsPkmn
{
    /**
     * @var string $id id des ivs
     *
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(name="id", type="string", length=6)
     */
    private $id;

    /**
     * @var integer $iv_hp iv en hp du pokemon
     *
     * @ORM\Column(name="iv_hp", type="integer", length=3)
     */
    private $iv_hp;

    /**
     * @var integer $iv_atk iv en attaque du pokemon
     *
     * @ORM\Column(name="iv_atk", type="integer", length=3)
     */
    private $iv_atk;

    /**
     * @var integer $iv_def iv en def du pokemon
     *
     * @ORM\Column(name="iv_def", type="integer", length=3)
     */
    private $iv_def;

    /**
     * @var integer $iv_spa iv en attaque spé du pokemon
     *
     * @ORM\Column(name="iv_spa", type="integer", length=3)
     */
    private $iv_spa;

    /**
     * @var integer $iv_spd iv en def spé du pokemon
     *
     * @ORM\Column(name="iv_spd", type="integer", length=3)
     */
    private $iv_spd;

    /**
     * @var integer $iv_spe iv en speedd du pokemon
     *
     * @ORM\Column(name="iv_spe", type="integer", length=3)
     */
    private $iv_spe;

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
    public function getIvHp(): int
    {
        return $this->iv_hp;
    }

    /**
     * @param int $iv_hp
     */
    public function setIvHp(int $iv_hp): void
    {
        $this->iv_hp = $iv_hp;
    }

    /**
     * @return int
     */
    public function getIvAtk(): int
    {
        return $this->iv_atk;
    }

    /**
     * @param int $iv_atk
     */
    public function setIvAtk(int $iv_atk): void
    {
        $this->iv_atk = $iv_atk;
    }

    /**
     * @return int
     */
    public function getIvDef(): int
    {
        return $this->iv_def;
    }

    /**
     * @param int $iv_def
     */
    public function setIvDef(int $iv_def): void
    {
        $this->iv_def = $iv_def;
    }

    /**
     * @return int
     */
    public function getIvSpa(): int
    {
        return $this->iv_spa;
    }

    /**
     * @param int $iv_spa
     */
    public function setIvSpa(int $iv_spa): void
    {
        $this->iv_spa = $iv_spa;
    }

    /**
     * @return int
     */
    public function getIvSpd(): int
    {
        return $this->iv_spd;
    }

    /**
     * @param int $iv_spd
     */
    public function setIvSpd(int $iv_spd): void
    {
        $this->iv_spd = $iv_spd;
    }

    /**
     * @return int
     */
    public function getIvSpe(): int
    {
        return $this->iv_spe;
    }

    /**
     * @param int $iv_spe
     */
    public function setIvSpe(int $iv_spe): void
    {
        $this->iv_spe = $iv_spe;
    }

}
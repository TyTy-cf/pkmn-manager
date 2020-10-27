<?php


namespace App\Entity\Stats;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Entity;

/**
 * Class EvsPkmn
 * @package App\Entity\Stats
 *
 * @ORM\Table(name="evs_pkmn")
 * @Entity
 */
class EvsPkmn
{
    /**
     * @var int $id id des evs
     *
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(name="id", type="integer", length=6)
     */
    private $id;

    /**
     * @var integer $ev_hp ev en hp du pokemon
     *
     * @ORM\Column(name="ev_hp", type="integer", length=3)
     */
    private $ev_hp;

    /**
     * @var integer $ev_atk ev en attaque du pokemon
     *
     * @ORM\Column(name="ev_atk", type="integer", length=3)
     */
    private $ev_atk;

    /**
     * @var integer $ev_def ev en def du pokemon
     *
     * @ORM\Column(name="ev_def", type="integer", length=3)
     */
    private $ev_def;

    /**
     * @var integer $ev_spa ev en attaque spé du pokemon
     *
     * @ORM\Column(name="ev_spa", type="integer", length=3)
     */
    private $ev_spa;

    /**
     * @var integer $ev_spd ev en def spé du pokemon
     *
     * @ORM\Column(name="ev_spd", type="integer", length=3)
     */
    private $ev_spd;

    /**
     * @var integer $ev_spe ev en speedd du pokemon
     *
     * @ORM\Column(name="ev_spe", type="integer", length=3)
     */
    private $ev_spe;

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
     * @return int
     */
    public function getEvHp(): int
    {
        return $this->ev_hp;
    }

    /**
     * @param int $ev_hp
     */
    public function setEvHp(int $ev_hp): void
    {
        $this->ev_hp = $ev_hp;
    }

    /**
     * @return int
     */
    public function getEvAtk(): int
    {
        return $this->ev_atk;
    }

    /**
     * @param int $ev_atk
     */
    public function setEvAtk(int $ev_atk): void
    {
        $this->ev_atk = $ev_atk;
    }

    /**
     * @return int
     */
    public function getEvDef(): int
    {
        return $this->ev_def;
    }

    /**
     * @param int $ev_def
     */
    public function setEvDef(int $ev_def): void
    {
        $this->ev_def = $ev_def;
    }

    /**
     * @return int
     */
    public function getEvSpa(): int
    {
        return $this->ev_spa;
    }

    /**
     * @param int $ev_spa
     */
    public function setEvSpa(int $ev_spa): void
    {
        $this->ev_spa = $ev_spa;
    }

    /**
     * @return int
     */
    public function getEvSpd(): int
    {
        return $this->ev_spd;
    }

    /**
     * @param int $ev_spd
     */
    public function setEvSpd(int $ev_spd): void
    {
        $this->ev_spd = $ev_spd;
    }

    /**
     * @return int
     */
    public function getEvSpe(): int
    {
        return $this->ev_spe;
    }

    /**
     * @param int $ev_spe
     */
    public function setEvSpe(int $ev_spe): void
    {
        $this->ev_spe = $ev_spe;
    }

}
<?php


namespace App\Entity\Stats;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Entity;

/**
 * Class StatsPkmn
 * @package App\Entity\Stats
 *
 * @Entity
 */
class StatsPkmn
{
    /**
     * @var string $id id des evs
     *
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(name="id", type="string", length=6)
     */
    private $id;

    /**
     * @var integer $stats_hp stats en hp du pokemon
     *
     * @ORM\Column(name="stats_hp", type="integer", length=3)
     */
    private $stats_hp;

    /**
     * @var integer $stats_atk stats en attaque du pokemon
     *
     * @ORM\Column(name="stats_atk", type="integer", length=3)
     */
    private $stats_atk;

    /**
     * @var integer $stats_def stats en def du pokemon
     *
     * @ORM\Column(name="stats_def", type="integer", length=3)
     */
    private $stats_def;

    /**
     * @var integer $stats_spa stats en attaque spé du pokemon
     *
     * @ORM\Column(name="stats_spa", type="integer", length=3)
     */
    private $stats_spa;

    /**
     * @var integer $stats_spd stats en def spé du pokemon
     *
     * @ORM\Column(name="stats_spd", type="integer", length=3)
     */
    private $stats_spd;

    /**
     * @var integer $stats_spe stats en speedd du pokemon
     *
     * @ORM\Column(name="stats_spe", type="integer", length=3)
     */
    private $stats_spe;

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
    public function getStatsHp(): int
    {
        return $this->stats_hp;
    }

    /**
     * @param int $stats_hp
     */
    public function setStatsHp(int $stats_hp): void
    {
        $this->stats_hp = $stats_hp;
    }

    /**
     * @return int
     */
    public function getStatsAtk(): int
    {
        return $this->stats_atk;
    }

    /**
     * @param int $stats_atk
     */
    public function setStatsAtk(int $stats_atk): void
    {
        $this->stats_atk = $stats_atk;
    }

    /**
     * @return int
     */
    public function getStatsDef(): int
    {
        return $this->stats_def;
    }

    /**
     * @param int $stats_def
     */
    public function setStatsDef(int $stats_def): void
    {
        $this->stats_def = $stats_def;
    }

    /**
     * @return int
     */
    public function getStatsSpa(): int
    {
        return $this->stats_spa;
    }

    /**
     * @param int $stats_spa
     */
    public function setStatsSpa(int $stats_spa): void
    {
        $this->stats_spa = $stats_spa;
    }

    /**
     * @return int
     */
    public function getStatsSpd(): int
    {
        return $this->stats_spd;
    }

    /**
     * @param int $stats_spd
     */
    public function setStatsSpd(int $stats_spd): void
    {
        $this->stats_spd = $stats_spd;
    }

    /**
     * @return int
     */
    public function getStatsSpe(): int
    {
        return $this->stats_spe;
    }

    /**
     * @param int $stats_spe
     */
    public function setStatsSpe(int $stats_spe): void
    {
        $this->stats_spe = $stats_spe;
    }

}
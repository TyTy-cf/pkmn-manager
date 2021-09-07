<?php


namespace App\Entity\Stats;


use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Entity;

/**
 * Class StatIv
 * @package App\Entity\Stats
 *
 * @ORM\Table(name="stats_iv")
 * @Entity
 */
class StatsIv
{
    /**
     * @var int $id id des ivs
     *
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(name="id", type="integer", length=6)
     */
    private int $id;

    /**
     * @var string $hp
     *
     * @ORM\Column(name="hp", type="string", length=10, nullable=true)
     */
    private string $hp;

    /**
     * @var string $atk
     *
     * @ORM\Column(name="atk", type="string", length=10, nullable=true)
     */
    private string $atk;

    /**
     * @var string $def
     *
     * @ORM\Column(name="def", type="string", length=10, nullable=true)
     */
    private string $def;

    /**
     * @var string $spa
     *
     * @ORM\Column(name="spa", type="string", length=10, nullable=true)
     */
    private string $spa;

    /**
     * @var string $spd
     *
     * @ORM\Column(name="spd", type="string", length=10, nullable=true)
     */
    private string $spd;

    /**
     * @var string $spe
     *
     * @ORM\Column(name="spe", type="string", length=10, nullable=true)
     */
    private string $spe;

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
    public function getHp(): string
    {
        return $this->hp;
    }

    /**
     * @param string $hp
     * @return StatsIv
     */
    public function setHp(string $hp): StatsIv
    {
        $this->hp = $hp;
        return $this;
    }

    /**
     * @return string
     */
    public function getAtk(): string
    {
        return $this->atk;
    }

    /**
     * @param string $atk
     * @return StatsIv
     */
    public function setAtk(string $atk): StatsIv
    {
        $this->atk = $atk;
        return $this;
    }

    /**
     * @return string
     */
    public function getDef(): string
    {
        return $this->def;
    }

    /**
     * @param string $def
     * @return StatsIv
     */
    public function setDef(string $def): StatsIv
    {
        $this->def = $def;
        return $this;
    }

    /**
     * @return string
     */
    public function getSpa(): string
    {
        return $this->spa;
    }

    /**
     * @param string $spa
     * @return StatsIv
     */
    public function setSpa(string $spa): StatsIv
    {
        $this->spa = $spa;
        return $this;
    }

    /**
     * @return string
     */
    public function getSpd(): string
    {
        return $this->spd;
    }

    /**
     * @param string $spd
     * @return StatsIv
     */
    public function setSpd(string $spd): StatsIv
    {
        $this->spd = $spd;
        return $this;
    }

    /**
     * @return string
     */
    public function getSpe(): string
    {
        return $this->spe;
    }

    /**
     * @param string $spe
     * @return StatsIv
     */
    public function setSpe(string $spe): StatsIv
    {
        $this->spe = $spe;
        return $this;
    }

}

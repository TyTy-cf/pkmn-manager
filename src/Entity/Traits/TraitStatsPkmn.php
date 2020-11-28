<?php


namespace App\Entity\Traits;


use Doctrine\ORM\Mapping as ORM;

trait TraitStatsPkmn
{

    /**
     * @var integer $hp
     *
     * @ORM\Column(name="hp", type="integer", length=3, nullable=true)
     */
    private $hp;

    /**
     * @var integer $atk
     *
     * @ORM\Column(name="atk", type="integer", length=3, nullable=true)
     */
    private $atk;

    /**
     * @var integer $def
     *
     * @ORM\Column(name="def", type="integer", length=3, nullable=true)
     */
    private $def;

    /**
     * @var integer $spa
     *
     * @ORM\Column(name="spa", type="integer", length=3, nullable=true)
     */
    private $spa;

    /**
     * @var integer $spd
     *
     * @ORM\Column(name="spd", type="integer", length=3, nullable=true)
     */
    private $spd;

    /**
     * @var integer $spe
     *
     * @ORM\Column(name="spe", type="integer", length=3, nullable=true)
     */
    private $spe;

    /**
     * @return int
     */
    public function getHp(): int
    {
        return $this->hp;
    }

    /**
     * @param int $hp
     */
    public function setHp(int $hp): void
    {
        $this->hp = $hp;
    }

    /**
     * @return int
     */
    public function getAtk(): int
    {
        return $this->atk;
    }

    /**
     * @param int $atk
     */
    public function setAtk(int $atk): void
    {
        $this->atk = $atk;
    }

    /**
     * @return int
     */
    public function getDef(): int
    {
        return $this->def;
    }

    /**
     * @param int $def
     */
    public function setDef(int $def): void
    {
        $this->def = $def;
    }

    /**
     * @return int
     */
    public function getSpa(): int
    {
        return $this->spa;
    }

    /**
     * @param int $spa
     */
    public function setSpa(int $spa): void
    {
        $this->spa = $spa;
    }

    /**
     * @return int
     */
    public function getSpd(): int
    {
        return $this->spd;
    }

    /**
     * @param int $spd
     */
    public function setSpd(int $spd): void
    {
        $this->spd = $spd;
    }

    /**
     * @return int
     */
    public function getSpe(): int
    {
        return $this->spe;
    }

    /**
     * @param int $spe
     */
    public function setSpe(int $spe): void
    {
        $this->spe = $spe;
    }
}
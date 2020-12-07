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
    private int $hp;

    /**
     * @var integer $atk
     *
     * @ORM\Column(name="atk", type="integer", length=3, nullable=true)
     */
    private int $atk;

    /**
     * @var integer $def
     *
     * @ORM\Column(name="def", type="integer", length=3, nullable=true)
     */
    private int $def;

    /**
     * @var integer $spa
     *
     * @ORM\Column(name="spa", type="integer", length=3, nullable=true)
     */
    private int $spa;

    /**
     * @var integer $spd
     *
     * @ORM\Column(name="spd", type="integer", length=3, nullable=true)
     */
    private int $spd;

    /**
     * @var integer $spe
     *
     * @ORM\Column(name="spe", type="integer", length=3, nullable=true)
     */
    private int $spe;

    /**
     * @return int
     */
    public function getHp(): int
    {
        return $this->hp;
    }

    /**
     * @param int $hp
     * @return TraitStatsPkmn
     */
    public function setHp(int $hp): self
    {
        $this->hp = $hp;
        return $this;
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
     * @return TraitStatsPkmn
     */
    public function setAtk(int $atk): self
    {
        $this->atk = $atk;
        return $this;
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
     * @return TraitStatsPkmn
     */
    public function setDef(int $def): self
    {
        $this->def = $def;
        return $this;
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
     * @return TraitStatsPkmn
     */
    public function setSpa(int $spa): self
    {
        $this->spa = $spa;
        return $this;
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
     * @return TraitStatsPkmn
     */
    public function setSpd(int $spd): self
    {
        $this->spd = $spd;
        return $this;
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
     * @return TraitStatsPkmn
     */
    public function setSpe(int $spe): self
    {
        $this->spe = $spe;
        return $this;
    }

}
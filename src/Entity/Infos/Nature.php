<?php


namespace App\Entity\Infos;

use App\Entity\Traits\TraitLanguage;
use App\Entity\Traits\TraitNames;
use App\Entity\Traits\TraitNomenclature;
use App\Entity\Traits\TraitSlug;
use App\Entity\Traits\TraitStatsPkmn;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Class Nature
 * @package App\Entity\Infos
 *
 * @ORM\Table(name="nature")
 * @Entity
 * @UniqueEntity(
 *     fields={"name"},
 *     message="Cette nature existe déjà !"
 * )
 */
class Nature
{

    public static string $ATK_API = 'attack';
    public static string $DEF_API = 'defense';
    public static string $SPA_API = 'special-attack';
    public static string $SPD_API = 'special-defense';
    public static string $SPE_API = 'speed';

    /**
     * @var int $id id de l'abstract info
     *
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(name="id", type="integer", length=6)
     */
    private int $id;

    use TraitNomenclature;

    /**
     * @var float $atk
     *
     * @ORM\Column(name="atk", type="float", length=3, nullable=true)
     */
    private float $atk;

    /**
     * @var float $def
     *
     * @ORM\Column(name="def", type="float", length=3, nullable=true)
     */
    private float $def;

    /**
     * @var float $spa
     *
     * @ORM\Column(name="spa", type="float", length=3, nullable=true)
     */
    private float $spa;

    /**
     * @var float $spd
     *
     * @ORM\Column(name="spd", type="float", length=3, nullable=true)
     */
    private float $spd;

    /**
     * @var float $spe
     *
     * @ORM\Column(name="spe", type="float", length=3, nullable=true)
     */
    private float $spe;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getAtk(): float
    {
        return $this->atk;
    }

    /**
     * @param float $atk
     * @return Nature
     */
    public function setAtk(float $atk): Nature
    {
        $this->atk = $atk;
        return $this;
    }

    /**
     * @return float
     */
    public function getDef(): float
    {
        return $this->def;
    }

    /**
     * @param float $def
     * @return Nature
     */
    public function setDef(float $def): Nature
    {
        $this->def = $def;
        return $this;
    }

    /**
     * @return float
     */
    public function getSpa(): float
    {
        return $this->spa;
    }

    /**
     * @param float $spa
     * @return Nature
     */
    public function setSpa(float $spa): Nature
    {
        $this->spa = $spa;
        return $this;
    }

    /**
     * @return float
     */
    public function getSpd(): float
    {
        return $this->spd;
    }

    /**
     * @param float $spd
     * @return Nature
     */
    public function setSpd(float $spd): Nature
    {
        $this->spd = $spd;
        return $this;
    }

    /**
     * @return float
     */
    public function getSpe(): float
    {
        return $this->spe;
    }

    /**
     * @param float $spe
     * @return Nature
     */
    public function setSpe(float $spe): Nature
    {
        $this->spe = $spe;
        return $this;
    }

}

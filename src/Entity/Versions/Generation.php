<?php


namespace App\Entity\Versions;


use App\Entity\Locations\Region;
use App\Entity\Traits\TraitNomenclature;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\OneToOne;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Versions\GenerationRepository")
 * @ORM\Table(name="generation")
 */
class Generation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    use TraitNomenclature;

    /**
     * @ORM\Column(type="string", length=2)
     */
    private string $number;

    /**
     * @ORM\Column(type="string", length=2)
     */
    private string $code;

    /**
     * @var Region
     *
     * @OneToOne(targetEntity="App\Entity\Locations\Region", inversedBy="generation")
     * @JoinColumn(name="main_region_id", referencedColumnName="id")
     */
    private Region $mainRegion;

    /**
     * @var array|string[]
     */
    public static array $relationArray = [
        1 => 'RB',
        2 => 'OA',
        3 => 'RS',
        4 => 'DP',
        5 => 'BW',
        6 => 'XY',
        7 => 'SM',
        8 => 'SS'
    ];

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getNumber(): string
    {
        return $this->number;
    }

    /**
     * @param string $number
     * @return Generation
     */
    public function setNumber(string $number): self
    {
        $this->number = $number;
        return $this;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @param string $code
     * @return Generation
     */
    public function setCode(string $code): self
    {
        $this->code = $code;
        return $this;
    }

    /**
     * @return Region
     */
    public function getMainRegion(): Region
    {
        return $this->mainRegion;
    }

    /**
     * @param Region $mainRegion
     * @return Generation
     */
    public function setMainRegion(Region $mainRegion): self
    {
        $this->mainRegion = $mainRegion;
        return $this;
    }

}
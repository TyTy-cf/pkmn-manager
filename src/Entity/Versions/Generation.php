<?php


namespace App\Entity\Versions;


use App\Entity\Traits\TraitLanguage;
use App\Entity\Traits\TraitNames;
use App\Entity\Traits\TraitNomenclature;
use App\Entity\Traits\TraitSlug;
use Doctrine\ORM\Mapping as ORM;

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
    public function setNumber(string $number): Generation
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
    public function setCode(string $code): Generation
    {
        $this->code = $code;
        return $this;
    }

}
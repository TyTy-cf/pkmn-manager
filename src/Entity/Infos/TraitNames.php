<?php


namespace App\Entity\Infos;


use Doctrine\ORM\Mapping as ORM;

trait TraitNames
{
    /**
     * @var string $nameEn
     *
     * @ORM\Column(name="name_en", type="string", length=24)
     */
    private $nameEn;

    /**
     * @var string $nameFr
     *
     * @ORM\Column(name="name_fr", type="string", length=24, nullable=true)
     */
    private $nameFr;

    /**
     * @return string
     */
    public function getNameEn(): string
    {
        return $this->nameEn;
    }

    /**
     * @param string $nameEn
     */
    public function setNameEn(string $nameEn): void
    {
        $this->nameEn = $nameEn;
    }

    /**
     * @return string
     */
    public function getNameFr(): string
    {
        return $this->nameFr;
    }

    /**
     * @param string $nameFr
     */
    public function setNameFr(string $nameFr): void
    {
        $this->nameFr = $nameFr;
    }

}
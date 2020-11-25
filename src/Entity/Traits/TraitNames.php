<?php


namespace App\Entity\Traits;


use Doctrine\ORM\Mapping as ORM;

trait TraitNames
{
    /**
     * @var string $nameEn
     *
     * @ORM\Column(name="name_en", type="string", length=36, nullable=true)
     */
    private string $nameEn;

    /**
     * @var string $nameFr
     *
     * @ORM\Column(name="name_fr", type="string", length=36, nullable=true)
     */
    private string $nameFr;

    /**
     * @var string $nameFr
     *
     * @ORM\Column(name="name", type="string", length=36, nullable=true)
     */
    private string $name;

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

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

}
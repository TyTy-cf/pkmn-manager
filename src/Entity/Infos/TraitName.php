<?php


namespace App\Entity\Infos;


use Doctrine\ORM\Mapping as ORM;

trait TraitName
{
    /**
     * @var string $name
     *
     * @ORM\Column(name="name", type="string", length=15)
     */
    private $name;

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
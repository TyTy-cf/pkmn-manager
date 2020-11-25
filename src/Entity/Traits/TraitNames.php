<?php


namespace App\Entity\Traits;


use Doctrine\ORM\Mapping as ORM;

trait TraitNames
{

    /**
     * @var string $name
     *
     * @ORM\Column(name="name", type="string", length=36, nullable=true)
     */
    private string $name;

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
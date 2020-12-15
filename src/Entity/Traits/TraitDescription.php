<?php


namespace App\Entity\Traits;


use Doctrine\ORM\Mapping as ORM;

trait TraitDescription
{
    /**
     * @var string|null $description
     *
     * @ORM\Column(name="description", type="string", length=255, nullable=true)
     */
    private ?string $description;

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string|null $description
     * @return TraitDescription
     */
    public function setDescription(?string $description): self
    {
        $this->description = $description;
        return $this;
    }

}
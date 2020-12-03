<?php


namespace App\Entity\Traits;


use Doctrine\ORM\Mapping as ORM;

trait TraitSlug
{

    /**
     * @var string $slug
     *
     * @ORM\Column(name="slug", type="string", length=120, nullable=true)
     */
    private string $slug;

    /**
     * @return string
     */
    public function getSlug(): string
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     */
    public function setSlug(string $slug): void
    {
        $this->slug = $slug;
    }

}
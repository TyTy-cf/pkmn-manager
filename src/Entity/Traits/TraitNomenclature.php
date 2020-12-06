<?php


namespace App\Entity\Traits;


use App\Entity\Users\Language;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;

trait TraitNomenclature
{
    /**
     * @var string $name
     *
     * @ORM\Column(name="name", type="string", length=36)
     */
    private string $name;

    /**
     * @var Language $language
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Users\Language")
     * @JoinColumn(name="language_id", referencedColumnName="id")
     */
    private Language $language;

    /**
     * @var string $slug
     *
     * @ORM\Column(name="slug", type="string", length=255)
     *
     */
    private string $slug;

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return TraitNomenclature
     */
    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return Language
     */
    public function getLanguage(): Language
    {
        return $this->language;
    }

    /**
     * @param Language $language
     * @return TraitNomenclature
     */
    public function setLanguage(Language $language): self
    {
        $this->language = $language;
        return $this;
    }

    /**
     * @return string
     */
    public function getSlug(): string
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     * @return TraitNomenclature
     */
    public function setSlug(string $slug): self
    {
        $this->slug = $slug;
        return $this;
    }

}
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
     */
    public function setName(string $name): void
    {
        $this->name = $name;
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
     */
    public function setLanguage(Language $language): void
    {
        $this->language = $language;
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
     */
    public function setSlug(string $slug): void
    {
        $this->slug = $slug;
    }

}
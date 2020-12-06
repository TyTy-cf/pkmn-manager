<?php


namespace App\Entity\Traits;


use App\Entity\Users\Language;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;

trait TraitLanguage
{

    /**
     * @var Language $language
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Users\Language")
     * @JoinColumn(name="language_id", referencedColumnName="id", nullable=true)
     */
    private Language $language;

    /**
     * @return Language
     */
    public function getLanguage(): Language
    {
        return $this->language;
    }

    /**
     * @param Language $language
     * @return TraitLanguage
     */
    public function setLanguage(Language $language): self
    {
        $this->language = $language;
        return $this;
    }

}
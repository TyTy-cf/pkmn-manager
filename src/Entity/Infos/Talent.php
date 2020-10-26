<?php


namespace App\Entity\Infos;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Entity;

/**
 * Class Talent
 *
 * @package App\Entity\Infos
 * @Entity
 */
class Talent
{
    /**
     * @var string $id id de l'abstract info
     *
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(name="id", type="string", length=6)
     */
    private $id;

    /**
     * @var string $nom nom de l'abstract info
     *
     * @ORM\Column(name="nom", type="string", length=48)
     */
    private $nom;

    /**
     * @var string $description la description du talent
     *
     * @ORM\Column(name="description", type="string", length=255)
     */
    private $description;

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     */
    public function setId(string $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getNom(): string
    {
        return $this->nom;
    }

    /**
     * @param string $nom
     */
    public function setNom(string $nom): void
    {
        $this->nom = $nom;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

}
<?php


namespace App\Entity\Infos;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Entity;

/**
 * Class Nature
 * @package App\Entity\Infos
 *
 * @ORM\Table(name="nature")
 * @Entity
 */
class Nature
{
    /**
     * @var int $id id de l'abstract info
     *
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(name="id", type="integer", length=6)
     */
    private $id;

    /**
     * @var string $nom nom de l'abstract info
     *
     * @ORM\Column(name="nom", type="string", length=12)
     */
    private $nom;

    /**
     * @var string $statsBonus la stats "bonus" de la nature
     *
     * @ORM\Column(name="statsBonus", type="string", length=12)
     */
    private $statsBonus;

    /**
     * @var string $statsMalus la stats "malus" de la nature
     *
     * @ORM\Column(name="statsMalus", type="string", length=12)
     */
    private $statsMalus;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
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
    public function getStatsBonus(): string
    {
        return $this->statsBonus;
    }

    /**
     * @param string $statsBonus
     */
    public function setStatsBonus(string $statsBonus): void
    {
        $this->statsBonus = $statsBonus;
    }

    /**
     * @return string
     */
    public function getStatsMalus(): string
    {
        return $this->statsMalus;
    }

    /**
     * @param string $statsMalus
     */
    public function setStatsMalus(string $statsMalus): void
    {
        $this->statsMalus = $statsMalus;
    }

}
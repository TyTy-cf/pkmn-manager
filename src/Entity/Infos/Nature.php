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
    private int $id;

    use TraitNames;

    /**
     * @var string $statsBonus la stats "bonus" de la nature
     *
     * @ORM\Column(name="stats_bonus", type="string", length=12, nullable=true)
     */
    private string $statsBonus;

    /**
     * @var string $statsPenalty
     *
     * @ORM\Column(name="stats_penalty", type="string", length=12, nullable=true)
     */
    private string $statsPenalty;

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
    public function getStatsPenalty(): string
    {
        return $this->statsPenalty;
    }

    /**
     * @param string $statsPenalty
     */
    public function setStatsPenalty(string $statsPenalty): void
    {
        $this->statsPenalty = $statsPenalty;
    }

}
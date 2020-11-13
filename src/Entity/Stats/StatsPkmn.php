<?php


namespace App\Entity\Stats;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Entity;

/**
 * Class StatsPkmn
 * @package App\Entity\Stats
 *
 * @ORM\Table(name="stats_pkmn")
 * @Entity
 */
class StatsPkmn
{
    /**
     * @var int $id id des evs
     *
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(name="id", type="integer", length=6)
     */
    private $id;

    use TraitStatsPkmn;

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

}
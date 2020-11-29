<?php


namespace App\Entity\Stats;


use App\Entity\Traits\TraitStatsPkmn;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Entity;

/**
 * Class StatsEffort
 * @package App\Entity\Stats
 *
 * @ORM\Table(name="stats_effort")
 * @Entity
 */
class StatsEffort
{
    /**
     * @var int $id
     *
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(name="id", type="integer", length=6)
     */
    private int $id;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    use TraitStatsPkmn;

}
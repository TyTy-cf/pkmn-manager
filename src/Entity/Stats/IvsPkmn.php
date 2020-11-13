<?php


namespace App\Entity\Stats;


use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Entity;

/**
 * Class IvsPkmn
 * @package App\Entity\Stats
 *
 * @ORM\Table(name="ivs_pkmn")
 * @Entity
 */
class IvsPkmn
{
    /**
     * @var int $id id des ivs
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
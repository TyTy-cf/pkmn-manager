<?php


namespace App\Entity\Pokedex;

use App\Repository\Pokedex\EvolutionChainChainRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;

/**
 * Class EvolutionChainChain
 * @package App\Entity\Pokedex
 * @ORM\Entity(repositoryClass=EvolutionChainChainRepository::class)
 */
class EvolutionChainChain
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @var EvolutionDetail
     * @ORM\ManyToOne(targetEntity="App\Entity\Pokedex\EvolutionDetail")
     * @JoinColumn(name="evolution_detail_from_id")
     */
    private EvolutionDetail $evolutionDetailFrom;

    /**
     * @var EvolutionDetail
     * @ORM\ManyToOne(targetEntity="App\Entity\Pokedex\EvolutionDetail")
     * @JoinColumn(name="evolution_detail_to_id")
     */
    private EvolutionDetail $evolutionDetailTo;

    /**
     * @ORM\Column(type="integer")
     */
    private int $order;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return EvolutionDetail
     */
    public function getEvolutionDetailFrom(): EvolutionDetail
    {
        return $this->evolutionDetailFrom;
    }

    /**
     * @param EvolutionDetail $evolutionDetailFrom
     * @return EvolutionChainChain
     */
    public function setEvolutionDetailFrom(EvolutionDetail $evolutionDetailFrom): EvolutionChainChain
    {
        $this->evolutionDetailFrom = $evolutionDetailFrom;
        return $this;
    }

    /**
     * @return EvolutionDetail
     */
    public function getEvolutionDetailTp(): EvolutionDetail
    {
        return $this->evolutionDetailTo;
    }

    /**
     * @param EvolutionDetail $evolutionDetailTo
     * @return EvolutionChainChain
     */
    public function setEvolutionDetailTp(EvolutionDetail $evolutionDetailTo): EvolutionChainChain
    {
        $this->evolutionDetailTo = $evolutionDetailTo;
        return $this;
    }

    /**
     * @return int
     */
    public function getOrder(): int
    {
        return $this->order;
    }

    /**
     * @param int $order
     * @return EvolutionChainChain
     */
    public function setOrder(int $order): EvolutionChainChain
    {
        $this->order = $order;
        return $this;
    }

}

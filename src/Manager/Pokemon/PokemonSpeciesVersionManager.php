<?php


namespace App\Manager\Pokemon;


use App\Repository\Pokemon\PokemonSpeciesVersionRepository;
use Doctrine\ORM\EntityManagerInterface;

class PokemonSpeciesVersionManager
{
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;

    /**
     * @var PokemonSpeciesVersionRepository
     */
    private PokemonSpeciesVersionRepository $repository;

    /**
     * PokemonManager constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param PokemonSpeciesVersionRepository $repository
     */
    public function __construct
    (
        EntityManagerInterface $entityManager,
        PokemonSpeciesVersionRepository $repository
    ) {
        $this->entityManager = $entityManager;
        $this->repository = $repository;
    }
}
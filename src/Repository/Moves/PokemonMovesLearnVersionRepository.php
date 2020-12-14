<?php


namespace App\Repository\Moves;


use App\Entity\Moves\MoveLearnMethod;
use App\Entity\Moves\MoveMachine;
use App\Entity\Moves\PokemonMovesLearnVersion;
use App\Entity\Pokemon\Pokemon;
use App\Entity\Versions\VersionGroup;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

class PokemonMovesLearnVersionRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PokemonMovesLearnVersion::class);
    }

    /**
     * @param string $slug
     * @return PokemonMovesLearnVersion|null
     * @throws NonUniqueResultException
     */
    public function getPokemonMovesLearnVersionByLanguageAndSlug(string $slug): ?PokemonMovesLearnVersion
    {
        return $this->createQueryBuilder('pokemon_moves_learn_version')
            ->where('pokemon_moves_learn_version.slug = :slug')
            ->setParameter('slug', $slug)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    /**
     * @return int|mixed|string
     */
    public function getLastPokemonIdInDataBase()
    {
        return $this->createQueryBuilder('pmlv')
            ->select('MAX(pmlv.pokemon)')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @param Pokemon $pokemon
     * @param $moveLearnMethod
     * @param $versionGroup
     * @return int|mixed|string
     */
    public function getMovesLearnBy(Pokemon $pokemon, MoveLearnMethod $moveLearnMethod, VersionGroup $versionGroup)
    {
        return $this->createQueryBuilder('pmlv')
            ->select('pmlv', 'move')
            ->join('pmlv.moveLearnMethod', 'moveLearnMethod')
            ->join('pmlv.move', 'move')
            ->join('pmlv.pokemon', 'pokemon')
            ->join('pmlv.versionGroup', 'versionGroup')
            ->where('moveLearnMethod = :moveLearnMethod')
            ->andWhere('pokemon = :pokemon')
            ->andWhere('versionGroup = :versionGroup')
            ->setParameter('moveLearnMethod', $moveLearnMethod)
            ->setParameter('pokemon', $pokemon)
            ->setParameter('versionGroup', $versionGroup)
            ->orderBy('pmlv.level', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @param Pokemon $pokemon
     * @param $moveLearnMethod
     * @param $versionGroup
     * @return int|mixed|string
     */
    public function getMovesLearnMachineBy(Pokemon $pokemon, MoveLearnMethod $moveLearnMethod, VersionGroup $versionGroup)
    {
        return $this->createQueryBuilder('pmlv')
            ->select('mm')
            ->join('pmlv.moveLearnMethod', 'moveLearnMethod')
            ->join('pmlv.move', 'move')
            ->join('pmlv.pokemon', 'pokemon')
            ->join('pmlv.versionGroup', 'versionGroup')
            ->join(MoveMachine::class, 'mm', 'WITH', 'mm.move = move')
            ->where('moveLearnMethod = :moveLearnMethod')
            ->andWhere('pokemon = :pokemon')
            ->andWhere('versionGroup = :versionGroup')
            ->andWhere('mm.versionGroup = :versionGroup')
            ->setParameter('moveLearnMethod', $moveLearnMethod)
            ->setParameter('pokemon', $pokemon)
            ->setParameter('versionGroup', $versionGroup)
            ->orderBy('mm.name', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }
}
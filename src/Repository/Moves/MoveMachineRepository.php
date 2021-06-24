<?php


namespace App\Repository\Moves;


use App\Entity\Moves\Move;
use App\Entity\Moves\MoveLearnMethod;
use App\Entity\Moves\MoveMachine;
use App\Entity\Moves\PokemonMovesLearnVersion;
use App\Entity\Pokemon\Pokemon;
use App\Entity\Versions\VersionGroup;
use App\Repository\AbstractRepository;
use Doctrine\Persistence\ManagerRegistry;

class MoveMachineRepository extends AbstractRepository
{
    /**
     * MoveMachineRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MoveMachine::class);
    }

    /**
     * @param Move $move
     * @return int|mixed|string
     */
    public function getMoveMachineByMove(Move $move)
    {
        return $this->createQueryBuilder('move_machine')
            ->select('move_machine', 'versionGroup')
            ->join('move_machine.move', 'move')
            ->join('move_machine.versionGroup', 'versionGroup')
            ->where('move = :move')
            ->setParameter('move', $move)
            ->orderBy('versionGroup.displayedOrder', 'DESC')
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
    public function getMovesMachineBy(Pokemon $pokemon, MoveLearnMethod $moveLearnMethod, VersionGroup $versionGroup)
    {
        return $this->createQueryBuilder('mm')
            ->select('mm', 'move', 'versionGroup', 'type', 'damageClass')
            ->join('mm.move', 'move')
            ->join('move.damageClass', 'damageClass')
            ->join('move.type', 'type')
            ->join('mm.versionGroup', 'versionGroup')
            ->join(PokemonMovesLearnVersion::class, 'pmlv', 'WITH', 'pmlv.move = move')
            ->join('pmlv.moveLearnMethod', 'moveLearnMethod')
            ->join('pmlv.versionGroup', 'versionGroup_2')
            ->join('pmlv.pokemon', 'pokemon')
            ->where('moveLearnMethod = :moveLearnMethod')
            ->andWhere('pokemon = :pokemon')
            ->andWhere('versionGroup = versionGroup_2')
            ->andWhere('versionGroup = :versionGroup')
            ->setParameter('moveLearnMethod', $moveLearnMethod)
            ->setParameter('pokemon', $pokemon)
            ->setParameter('versionGroup', $versionGroup)
            ->orderBy('mm.number', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }
}

<?php


namespace App\Repository\Moves;


use App\Entity\Moves\Move;
use App\Entity\Moves\MoveMachine;
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
     * @param $array
     * @return int|mixed|string
     */
    public function getMachineByMove(Move $move)
    {
        return $this->createQueryBuilder('move_machine')
            ->select('move_machine', 'versionGroup')
            ->join('move_machine.move', 'move')
            ->join('move_machine.versionGroup', 'versionGroup')
            ->where('move = :move')
            ->setParameter('move', $move)
            ->orderBy('versionGroup.order', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }
}
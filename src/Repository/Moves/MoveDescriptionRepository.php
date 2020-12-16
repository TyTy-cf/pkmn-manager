<?php


namespace App\Repository\Moves;


use App\Entity\Moves\Move;
use App\Entity\Moves\MoveDescription;
use App\Repository\AbstractRepository;
use Doctrine\Persistence\ManagerRegistry;

class MoveDescriptionRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MoveDescription::class);
    }

    /**
     * @param Move $move
     * @return int|mixed|string
     */
    public function getMoveDescriptionByMove(Move $move)
    {
        return $this->createQueryBuilder('move_description')
            ->select('move_description', 'versionGroup')
            ->join('move_description.move', 'move')
            ->join('move_description.versionGroup', 'versionGroup')
            ->where('move = :move')
            ->setParameter('move', $move)
            ->orderBy('versionGroup.order', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

}
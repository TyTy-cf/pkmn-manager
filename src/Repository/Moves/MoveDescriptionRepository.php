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
     * @param $array
     * @return int|mixed|string
     */
    public function getMoveDescriptionByMove(Move $move, $array)
    {
        return $this->createQueryBuilder('move_description')
            ->select('move_description', 'versionGroup')
            ->join('move_description.move', 'move')
            ->join('move_description.versionGroup', 'versionGroup')
            ->where('move = :move')
            ->andWhere('versionGroup.name NOT IN (:array)')
            ->setParameter('array', $array)
            ->setParameter('move', $move)
            ->orderBy('versionGroup.order', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

}
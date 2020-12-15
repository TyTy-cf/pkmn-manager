<?php


namespace App\Repository\Moves;


use App\Entity\Moves\Move;
use App\Repository\AbstractRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Class MoveRepository
 * @package App\Repository\Infos
 */
class MoveRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Move::class);
    }

    /**
     * @param string $slug
     * @return int|mixed|string
     * @throws NonUniqueResultException
     */
    public function getSimpleMoveBySlug(string $slug)
    {
        return $this->createQueryBuilder('move')
            ->select('move')
            ->where('move.slug = :slug')
            ->setParameter('slug', $slug)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
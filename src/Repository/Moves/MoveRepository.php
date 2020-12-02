<?php


namespace App\Repository\Moves;


use App\Entity\Moves\Move;
use App\Entity\Users\Language;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Class MoveRepository
 * @package App\Repository\Infos
 */
class MoveRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Move::class);
    }

    /**
     * @param Language $language
     * @param string $slug
     * @return Move|null
     * @throws NonUniqueResultException
     */
    public function getMoveByLanguageAndSlug(Language $language, string $slug): ?Move
    {
        $qb = $this->createQueryBuilder('move');
        $qb->where('move.language = :lang');
        $qb->andWhere('move.slug = :slug');
        $qb->setParameter('lang', $language);
        $qb->setParameter('slug', $slug);
        return $qb->getQuery()->getOneOrNullResult();
    }
}
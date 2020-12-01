<?php


namespace App\Repository\Moves;


use App\Entity\Moves\MoveDescription;
use App\Entity\Users\Language;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

class MoveDescriptionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MoveDescription::class);
    }

    /**
     * @param Language $language
     * @param string $slug
     * @return MoveDescription|null
     * @throws NonUniqueResultException
     */
    public function getMoveDescriptionByLanguageAndSlug(Language $language, string $slug)
    {
        $qb = $this->createQueryBuilder('move_description');
        $qb->where('move_description.language = :lang');
        $qb->andWhere('move_description.slug = :slug');
        $qb->setParameter('lang', $language);
        $qb->setParameter('slug', $slug);
        return $qb->getQuery()->getOneOrNullResult();
    }

}
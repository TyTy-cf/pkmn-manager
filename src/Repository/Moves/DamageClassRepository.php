<?php


namespace App\Repository\Moves;

use App\Entity\Moves\DamageClass;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Class DamageClassRepository
 * @package App\Repository\Moves
 */
class DamageClassRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DamageClass::class);
    }

    /**
     * @param string $lang
     * @param string $slug
     * @return int|mixed|string|null
     * @throws NonUniqueResultException
     */
    public function getDamageClassByLanguageAndSlug(string $lang, string $slug)
    {
        $qb = $this->createQueryBuilder('damage_class');
        $qb->join('damage_class.language', 'language');
        $qb->where('language.code = :lang');
        $qb->andWhere('damage_class.slug = :slug');
        $qb->setParameter('lang', $lang);
        $qb->setParameter('slug', $slug);
        return $qb->getQuery()->getOneOrNullResult();
    }
}
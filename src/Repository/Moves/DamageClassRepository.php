<?php


namespace App\Repository\Moves;

use App\Entity\Moves\DamageClass;
use App\Entity\Users\Language;
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
     * @param Language $language
     * @param string $slug
     * @return DamageClass|null
     * @throws NonUniqueResultException
     */
    public function getDamageClassByLanguageAndSlug(Language $language, string $slug): ?DamageClass
    {
        $qb = $this->createQueryBuilder('damage_class');
        $qb->where('damage_class.language = :lang');
        $qb->andWhere('damage_class.slug = :slug');
        $qb->setParameter('lang', $language);
        $qb->setParameter('slug', $slug);
        return $qb->getQuery()->getOneOrNullResult();
    }
}
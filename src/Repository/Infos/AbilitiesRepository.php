<?php

namespace App\Repository\Infos;

use App\Entity\Infos\Abilities;
use App\Entity\Users\Language;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Abilities|null find($id, $lockMode = null, $lockVersion = null)
 * @method Abilities|null findOneBy(array $criteria, array $orderBy = null)
 * @method Abilities[]    findAll()
 * @method Abilities[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AbilitiesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Abilities::class);
    }

    /**
     * @param Language $language
     * @param string $slug
     * @return Abilities|null
     * @throws NonUniqueResultException
     */
    public function getAbilitiesByLanguageAndSlug(Language $language, string $slug): ?Abilities
    {
        $qb = $this->createQueryBuilder('abilities');
        $qb->join('abilities.language', 'language');
        $qb->where('language = :lang');
        $qb->andWhere('abilities.slug = :slug');
        $qb->setParameter('lang', $language);
        $qb->setParameter('slug', $slug);
        return $qb->getQuery()->getOneOrNullResult();
    }
}

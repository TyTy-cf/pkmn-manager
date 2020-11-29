<?php

namespace App\Repository\Versions;

use App\Entity\Users\Language;
use App\Entity\Versions\VersionGroup;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method VersionGroup|null find($id, $lockMode = null, $lockVersion = null)
 * @method VersionGroup|null findOneBy(array $criteria, array $orderBy = null)
 * @method VersionGroup[]    findAll()
 * @method VersionGroup[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VersionGroupRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VersionGroup::class);
    }

    /**
     * @param Language $language
     * @param string $slug
     * @return int|mixed|string|null
     * @throws NonUniqueResultException
     */
    public function getVersionGroupByLanguageAndSlug(Language $language, string $slug)
    {
        $qb = $this->createQueryBuilder('version_group');
        $qb->join('version_group.language', 'language');
        $qb->where('language = :lang');
        $qb->andWhere('version_group.slug = :slug');
        $qb->setParameter('lang', $language);
        $qb->setParameter('slug', $slug);
        return $qb->getQuery()->getOneOrNullResult();
    }
}

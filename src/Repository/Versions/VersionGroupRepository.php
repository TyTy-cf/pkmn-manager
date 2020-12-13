<?php

namespace App\Repository\Versions;

use App\Entity\Users\Language;
use App\Entity\Versions\Generation;
use App\Entity\Versions\VersionGroup;
use App\Repository\AbstractRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method VersionGroup|null find($id, $lockMode = null, $lockVersion = null)
 * @method VersionGroup|null findOneBy(array $criteria, array $orderBy = null)
 * @method VersionGroup[]    findAll()
 * @method VersionGroup[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VersionGroupRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VersionGroup::class);
    }

    /**
     * @param Language $language
     * @param string $name
     * @return int|mixed|string|null
     * @throws NonUniqueResultException
     */
    public function getVersionGroupByLanguageAndName(Language $language, string $name)
    {
        return $this->createQueryBuilder('version_group')
            ->where('version_group.language = :lang')
            ->andWhere('version_group.name = :name')
            ->setParameter('lang', $language)
            ->setParameter('name', $name)
            ->getQuery()->getOneOrNullResult()
        ;
    }

    /**
     * @param Generation $generation
     * @param Language $language
     * @return int|mixed|string
     */
    public function getVersionGroupByGenerationAndLanguage(Generation $generation, Language $language)
    {
        return $this->createQueryBuilder('version_group')
            ->select('version_group')
            ->join('version_group.generation', 'generation')
            ->join('version_group.language', 'language')
            ->where('generation = :generation')
            ->andWhere('language = :language')
            ->setParameter('language', $language)
            ->setParameter('generation', $generation)
            ->orderBy('generation.number', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @param Language $language
     * @return int|mixed|string
     */
    public function getVersionGroupByLanguage(Language $language)
    {
        return $this->createQueryBuilder('version_group')
            ->select('version_group')
            ->join('version_group.language', 'language')
            ->where('language = :language')
            ->setParameter('language', $language)
            ->orderBy('version_group.order', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }
}

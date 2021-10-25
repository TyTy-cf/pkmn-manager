<?php


namespace App\Repository;


use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

class AbstractRepository extends ServiceEntityRepository
{
    /**
     * AbstractRepository constructor.
     * @param ManagerRegistry $registry
     * @param string $entityClass
     */
    public function __construct(ManagerRegistry $registry, string $entityClass = '')
    {
        parent::__construct($registry, $entityClass);
    }

    /**
     * @param string $slug
     * @param string $codeLanguage
     * @return object|null
     * @throws NonUniqueResultException
     */
    public function findOneBySlugAndLanguage(string $slug, string $codeLanguage): ?object
    {
        return $this->createQueryBuilder('entity')
            ->select('entity')
            ->join('entity.language', 'language')
            ->where('language.code = :code')
            ->andWhere('entity.slug = :slug')
            ->setParameter('code', $codeLanguage)
            ->setParameter('slug', $slug)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    /**
     * @param string $slug
     * @return object|null
     */
    public function findOneBySlug(string $slug): ?object
    {
        return $this->findOneBy([
            'slug' => $slug
        ]);
    }
}

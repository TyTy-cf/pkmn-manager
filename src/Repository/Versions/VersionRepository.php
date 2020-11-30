<?php


namespace App\Repository\Versions;


use App\Entity\Users\Language;
use App\Entity\Versions\Version;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Version|null find($id, $lockMode = null, $lockVersion = null)
 * @method Version|null findOneBy(array $criteria, array $orderBy = null)
 * @method Version[]    findAll()
 * @method Version[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VersionRepository  extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Version::class);
    }

    /**
     * @param Language $language
     * @param string $slug
     * @return int|mixed|string|null
     * @throws NonUniqueResultException
     */
    public function getVersionByLanguageAndSlug(Language $language, string $slug)
    {
        $qb = $this->createQueryBuilder('version');
        $qb->join('version.language', 'language');
        $qb->where('language = :lang');
        $qb->andWhere('version.slug = :slug');
        $qb->setParameter('lang', $language);
        $qb->setParameter('slug', $slug);
        return $qb->getQuery()->getOneOrNullResult();
    }

}
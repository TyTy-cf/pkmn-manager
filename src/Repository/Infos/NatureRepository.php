<?php


namespace App\Repository\Infos;


use App\Entity\Infos\Nature;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Nature|null find($id, $lockMode = null, $lockVersion = null)
 * @method Nature|null findOneBy(array $criteria, array $orderBy = null)
 * @method Nature[]    findAll()
 * @method Nature[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NatureRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Nature::class);
    }

    /**
     * @param string $lang
     * @param $slug
     * @return Nature|null
     * @throws NonUniqueResultException
     */
    public function getNatureByLanguageAndSlug(string $lang, $slug): ?Nature
    {
        $qb = $this->createQueryBuilder('nature');
        $qb->join('nature.language', 'language');
        $qb->where('language.code = :lang');
        $qb->andWhere('nature.slug = :slug');
        $qb->setParameter('lang', $lang);
        $qb->setParameter('slug', $slug);
        return $qb->getQuery()->getOneOrNullResult();
    }
}
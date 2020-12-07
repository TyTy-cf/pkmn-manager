<?php


namespace App\Repository\Versions;


use App\Entity\Users\Language;
use App\Entity\Versions\Generation;
use App\Repository\AbstractRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Generation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Generation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Generation[]    findAll()
 * @method Generation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GenerationRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Generation::class);
    }

    /**
     * @param Language $language
     * @param string $number
     * @return int|mixed|string|null
     * @throws NonUniqueResultException
     */
    public function getGenerationByLanguageAndGenerationNumber
    (
        Language $language, string $number
    ): ?Generation
    {
        $qb = $this->createQueryBuilder('generation');
        $qb->join('generation.language', 'language');
        $qb->where('language = :lang');
        $qb->andWhere('generation.number = :number');
        $qb->setParameter('lang', $language);
        $qb->setParameter('number', $number);
        return $qb->getQuery()->getOneOrNullResult();
    }
}
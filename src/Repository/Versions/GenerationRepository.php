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
        return $this->createQueryBuilder('generation')
            ->select('generation')
            ->join('generation.language', 'language')
            ->where('language = :lang')
            ->andWhere('generation.number = :number')
            ->setParameter('lang', $language)
            ->setParameter('number', $number)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    /**
     * @param Language $language
     * @return int|mixed|string
     */
    public function getGenerationByLanguage(Language $language)
    {
        return $this->createQueryBuilder('generation')
            ->select('generation', 'region')
            ->join('generation.language', 'language')
            ->join('generation.mainRegion', 'region')
            ->where('language = :language')
            ->setParameter(':language', $language)
            ->orderBy('generation.displayOrder')
            ->getQuery()
            ->getResult()
        ;
    }

}
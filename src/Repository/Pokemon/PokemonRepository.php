<?php

namespace App\Repository\Pokemon;

use App\Entity\Pokemon\Pokemon;
use App\Entity\Users\Language;
use App\Repository\AbstractRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Pokemon|null find($id, $lockMode = null, $lockVersion = null)
 * @method Pokemon|null findOneBy(array $criteria, array $orderBy = null)
 * @method Pokemon[]    findAll()
 * @method Pokemon[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PokemonRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Pokemon::class);
    }

    /**
     * @param Language $language
     * @param int $offset
     * @param int $limit
     * @return array|int|string
     */
    public function getPokemonOffsetLimitApiCodeByLanguage
    (
        Language $language,
        int $offset,
        int $limit
    )
    {
        return $this->createQueryBuilder('pokemon')
            ->select('pokemon')
            ->where('pokemon.language = :lang')
            ->setParameter('lang', $language)
            ->setMaxResults($limit)
            ->setFirstResult($offset)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param Language $language
     * @param string $slug
     * @return Pokemon|null
     * @throws NonUniqueResultException
     * @throws NonUniqueResultException
     */
    public function getPokemonByLanguageAndSlug(Language $language, string $slug): ?Pokemon
    {
        return $this->createQueryBuilder('pokemon')
            ->where('pokemon.language = :lang')
            ->andWhere('pokemon.slug = :slug')
            ->setParameter('lang', $language)
            ->setParameter('slug', $slug)
            ->getQuery()->getOneOrNullResult();
    }

    /**
     * @param string $name
     * @param string $languageCode
     * @return int|mixed|string|null
     * @throws NonUniqueResultException
     */
    public function getPokemonByNameAndLanguageCode(string $name, string $languageCode)
    {
        return $this->createQueryBuilder('pokemon')
            ->join('pokemon.language', 'language')
            ->where('language.code = :lang')
            ->andWhere('pokemon.name = :name')
            ->setParameter('lang', $languageCode)
            ->setParameter('name', $name)
            ->getQuery()->getOneOrNullResult();
    }

}

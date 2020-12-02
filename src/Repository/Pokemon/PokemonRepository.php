<?php

namespace App\Repository\Pokemon;

use App\Entity\Pokemon\Pokemon;
use App\Entity\Users\Language;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Pokemon|null find($id, $lockMode = null, $lockVersion = null)
 * @method Pokemon|null findOneBy(array $criteria, array $orderBy = null)
 * @method Pokemon[]    findAll()
 * @method Pokemon[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PokemonRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Pokemon::class);
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
        $qb = $this->createQueryBuilder('pokemon');
        $qb->where('pokemon.language = :lang');
        $qb->andWhere('pokemon.slug = :slug');
        $qb->setParameter('lang', $language);
        $qb->setParameter('slug', $slug);
        return $qb->getQuery()->getOneOrNullResult();
    }

    /**
     * @param string $name
     * @param string $languageCode
     * @return int|mixed|string|null
     * @throws NonUniqueResultException
     */
    public function getPokemonByNameAndLanguageCode(string $name, string $languageCode)
    {
        $qb = $this->createQueryBuilder('pokemon');
        $qb->join('pokemon.language', 'language');
        $qb->where('language.code = :lang');
        $qb->andWhere('pokemon.name = :name');
        $qb->setParameter('lang', $languageCode);
        $qb->setParameter('name', $name);
        return $qb->getQuery()->getOneOrNullResult();
    }

}

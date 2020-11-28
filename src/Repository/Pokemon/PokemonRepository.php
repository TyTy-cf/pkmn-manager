<?php

namespace App\Repository\Pokemon;

use App\Entity\Pokemon\Pokemon;
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
     * @param string $lang
     * @param string $slug
     * @return int|mixed|string|null
     * @throws NonUniqueResultException
     * @throws NonUniqueResultException
     */
    public function getPokemonByLanguageAndSlug(string $lang, string $slug)
    {
        $qb = $this->createQueryBuilder('pokemon');
        $qb->join('pokemon.language', 'language');
        $qb->where('language.code = :lang');
        $qb->andWhere('pokemon.slug = :slug');
        $qb->setParameter('lang', $lang);
        $qb->setParameter('slug', $slug);
        return $qb->getQuery()->getOneOrNullResult();
    }

}

<?php

namespace App\Repository\Pokemon;

use App\Entity\Locations\Region;
use App\Entity\Pokemon\Pokemon;
use App\Entity\Pokemon\PokemonForm;
use App\Entity\Users\Language;
use App\Entity\Versions\Generation;
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
     * @return int|mixed|string
     */
    public function getAllPokemonByLanguage(Language $language)
    {
        return $this->createQueryBuilder('pokemon')
            ->select('pokemon')
            ->where('pokemon.language = :lang')
            ->setParameter('lang', $language)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @param Language $language
     * @param int $offset
     * @param int $limit
     * @return array|int|string
     */
    public function getPokemonOffsetLimitByLanguage
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
            ->getResult()
        ;
    }

    /**
     * @param string $slug
     * @return int|mixed|string|null
     * @throws NonUniqueResultException
     */
    public function getPokemonPofileBySlug(string $slug)
    {
        return $this->createQueryBuilder('p')
            ->select('p', 'ps', 'eg', 'se')
            ->leftJoin('p.pokemonSpecies', 'ps')
            ->leftJoin('ps.eggGroup', 'eg')
            ->leftJoin('p.statsEffort', 'se')
            ->andWhere('p.slug = :slug')
            ->setParameter('slug', $slug)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    /**
     * @param Language $language
     * @return int|mixed|string
     */
    public function getAllPokemonNameForLanguage(Language $language)
    {
        return $this->createQueryBuilder('pokemon')
            ->select('pokemon.name')
            ->where('pokemon.language = :language')
            ->setParameter('language', $language)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @param Language $language
     * @return int|mixed|string
     */
    public function getAllPokemonsListByLanguage(Language $language)
    {
        return $this->createQueryBuilder('pokemon')
            ->select('pokemon', 'types')
            ->leftJoin('pokemon.pokemonSprites', 'pokemonSprites')
            ->join('pokemon.types', 'types')
            ->where('pokemon.language = :lang')
            ->setParameter('lang', $language)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @param Generation $generation
     * @param Language|null $language
     * @return int|mixed|string
     */
    public function getPokemonsByGenerationAndLanguage(Generation $generation, Language $language)
    {
        return $this->createQueryBuilder('pokemon')
            ->select('pokemon', 'types', 'sprites')
            ->join('pokemon.types', 'types')
            ->join('pokemon.pokemonSprites', 'sprites')
            ->join('pokemon.pokemonSpecies', 'pokemonSpecies')
            ->join('pokemonSpecies.generation', 'generation')
            ->where('pokemon.language = :language')
            ->andWhere('generation = :generation')
            ->setParameter('generation', $generation)
            ->setParameter('language', $language)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @param Region $region
     * @param array $versionGroupOrder
     * @param Language $language
     * @return int|mixed|string
     */
    public function getPokemonsByRegion(Region $region, array $versionGroupOrder, Language $language)
    {
        $qb = $this->createQueryBuilder('pokemon')
            ->select('pokemon', 'pokemon_species', 'pokemon_sprites', 'pokedex_species', 'forms')
            ->join('pokemon.pokemonSpecies', 'pokemon_species')
            ->join('pokemon.pokemonSprites', 'pokemon_sprites')
            ->join('pokemon_species.pokedexSpecies', 'pokedex_species')
            ->join('pokedex_species.pokedex', 'pokedex')
            ->leftJoin('pokemon.pokemonForms', 'forms')
            ->where('pokedex.region = :region')
            ->andwhere('pokemon.language = :language')
            ->setParameter('region', $region)
            ->setParameter('language', $language)
            ->orderBy('pokedex_species.number', 'ASC')
            ->getQuery()
            ->getResult()
        ;

        // Remove pokemon with form from newest generations
        foreach ($qb as $key => $pokemon) {
            /** @var Pokemon $pokemon */
            $pokemonForms = $pokemon->getPokemonForms();
            if (sizeof($pokemonForms) !== 0) {
                foreach($pokemonForms as $form) {
                    foreach($versionGroupOrder as $vgOrder) {
                        /** @var PokemonForm $form */
                        if ($form->getVersionGroup()->getId() >= $vgOrder['order']) {
                            unset($qb[$key]);
                        }
                    }
                }
            }
        }

        return $qb;
    }

}

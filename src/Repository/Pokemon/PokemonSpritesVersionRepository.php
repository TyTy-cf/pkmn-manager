<?php


namespace App\Repository\Pokemon;


use App\Entity\Pokemon\Pokemon;
use App\Entity\Pokemon\PokemonSpritesVersion;
use App\Entity\Versions\VersionGroup;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PokemonSpritesVersion|null find($id, $lockMode = null, $lockVersion = null)
 * @method PokemonSpritesVersion|null findOneBy(array $criteria, array $orderBy = null)
 * @method PokemonSpritesVersion[]    findAll()
 * @method PokemonSpritesVersion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PokemonSpritesVersionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PokemonSpritesVersion::class);
    }

    /**
     * @param VersionGroup $versionGroup
     * @param Pokemon $pokemon
     * @return int|mixed|string
     */
    public function getSpritesByVersionGroupIdAndPokemon(VersionGroup $versionGroup, Pokemon $pokemon)
    {
        return $this->createQueryBuilder('pokemon_sprites_version')
            ->select('pokemon_sprites_version.urlDefault', 'pokemon_sprites_version.urlDefaultShiny')
            ->join('pokemon_sprites_version.versionGroup', 'versionGroup')
            ->where('versionGroup = :vg')
            ->andWhere('pokemon_sprites_version.pokemon = :pokemon')
            ->setParameter('vg', $versionGroup)
            ->setParameter('pokemon', $pokemon)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @param Pokemon $pokemon
     * @return int|mixed|string
     */
    public function getSpritesVersionGroupByPokemon(Pokemon $pokemon)
    {
        return $this->createQueryBuilder('pokemon_sprites_version')
            ->select('pokemon_sprites_version', 'versionGroup')
            ->join('pokemon_sprites_version.versionGroup', 'versionGroup')
            ->where('pokemon_sprites_version.pokemon = :pokemon')
            ->setParameter('pokemon', $pokemon)
            ->orderBy('versionGroup.displayedOrder', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }
}

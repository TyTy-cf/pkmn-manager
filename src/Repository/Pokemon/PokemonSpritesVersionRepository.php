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
        return $this->createQueryBuilder('psv')
            ->select('psv.urlDefault', 'psv.urlDefaultShiny')
            ->join('psv.versionGroup', 'versionGroup')
            ->where('versionGroup = :vg')
            ->andWhere('psv.pokemon = :pokemon')
            ->setParameter('vg', $versionGroup)
            ->setParameter('pokemon', $pokemon)
            ->getQuery()
            ->getResult()
        ;
    }
}
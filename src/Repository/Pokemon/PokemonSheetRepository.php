<?php


namespace App\Repository\Pokemon;


use App\Entity\Pokemon\PokemonSheet;
use App\Repository\AbstractRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PokemonSheet|null find($id, $lockMode = null, $lockVersion = null)
 * @method PokemonSheet|null findOneBy(array $criteria, array $orderBy = null)
 * @method PokemonSheet[]    findAll()
 * @method PokemonSheet[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PokemonSheetRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, PokemonSheet::class);
    }

    /**
     * @param int $id
     * @return PokemonSheet|null
     * @throws NonUniqueResultException
     */
    public function findByIdWithRelations(int $id): ?PokemonSheet
    {
        return $this->createQueryBuilder('ps')
            ->select('ps', 'ability', 'moves', 'nature', 'gender', 'pokemon')
            ->join('ps.ability', 'ability')
            ->join('ps.nature', 'nature')
            ->join('ps.gender', 'gender')
            ->join('ps.pokemon', 'pokemon')
            ->leftJoin('ps.moves', 'moves')
            ->where('ps.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}

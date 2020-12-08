<?php


namespace App\Repository\Items;


use App\Entity\Items\ItemHeldPokemonVersion;
use App\Repository\AbstractRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ItemHeldPokemonVersion|null find($id, $lockMode = null, $lockVersion = null)
 * @method ItemHeldPokemonVersion|null findOneBy(array $criteria, array $orderBy = null)
 * @method ItemHeldPokemonVersion[]    findAll()
 * @method ItemHeldPokemonVersion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ItemHeldPokemonVersionRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ItemHeldPokemonVersion::class);
    }
}

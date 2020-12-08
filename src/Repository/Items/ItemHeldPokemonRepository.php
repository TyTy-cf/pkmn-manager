<?php

namespace App\Repository\Items;

use App\Entity\Items\ItemHeldPokemon;
use App\Repository\AbstractRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ItemHeldPokemon|null find($id, $lockMode = null, $lockVersion = null)
 * @method ItemHeldPokemon|null findOneBy(array $criteria, array $orderBy = null)
 * @method ItemHeldPokemon[]    findAll()
 * @method ItemHeldPokemon[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ItemHeldPokemonRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ItemHeldPokemon::class);
    }
}

<?php


namespace App\Repository\Items;


use App\Entity\Items\ItemDescription;
use App\Repository\AbstractRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ItemDescription|null find($id, $lockMode = null, $lockVersion = null)
 * @method ItemDescription|null findOneBy(array $criteria, array $orderBy = null)
 * @method ItemDescription[]    findAll()
 * @method ItemDescription[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ItemDescriptionRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ItemDescription::class);
    }
}

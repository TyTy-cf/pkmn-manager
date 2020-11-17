<?php


namespace App\Manager;


use App\Entity\Infos\Type;
use App\Entity\Pokemon\Pokemon;
use App\Repository\TypeRepository;
use Doctrine\ORM\EntityManagerInterface;

class TypeManager
{
    /**
     * @var TypeRepository
     */
    private $typeRepository;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * PokemonManager constructor.
     *
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->typeRepository = $this->entityManager->getRepository(Type::class);
    }

    /**
     * @param $types
     * @param Pokemon $pokemon
     */
    public function saveNewTypes($types, Pokemon $pokemon)
    {
        // Iterate the types from the json, create the type if not existing or get it
        foreach ($types as $type) {
            $typeName = $type['type']['name'];
            if (($newType = $this->typeRepository->findOneBy(['nameEn' => $typeName])) == null) {
                $newType = new Type();
                $newType->setNameEn(ucfirst($typeName));
                $this->entityManager->persist($newType);
            }
            $pokemon->addType($newType);
            $this->entityManager->flush();
        }
    }
}
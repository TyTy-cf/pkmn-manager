<?php

namespace App\Manager\Infos\Type;

use App\Entity\Infos\Type\TypeDamageFromType;
use App\Repository\Infos\Type\TypeDamageFromTypeRepository;
use App\Repository\Infos\Type\TypeRepository;
use Doctrine\ORM\EntityManagerInterface;

class TypeDamageFromTypeManager
{
    /**
     * @var TypeDamageFromTypeRepository
     */
    private TypeDamageFromTypeRepository $typeDamageFromTypeRepository;

    /**
     * @var TypeRepository
     */
    private TypeRepository $typeRepository;

    /**
     * @var EntityManagerInterface $em
     */
    private EntityManagerInterface $em;

    /**
     * TypeDamageFromTypeRepository constructor
     * @param TypeDamageFromTypeRepository $typeDamageFromTypeRepository
     * @param TypeRepository $typeRepository
     * @param EntityManagerInterface $em
     */
    public function __construct(
        TypeDamageFromTypeRepository $typeDamageFromTypeRepository,
        TypeRepository $typeRepository,
        EntityManagerInterface $em
    ) {
        $this->typeDamageFromTypeRepository = $typeDamageFromTypeRepository;
        $this->typeRepository = $typeRepository;
        $this->em = $em;
    }

    /**
     * Create DamageFromType if not exist
     * @param $type
     * @param $damageFromType
     * @param $coef
     * @return TypeDamageFromType
     */
    public function createDamageFromTypeIfNotExist(object $type, string $damageFromType, int $coef)
    {
        //Check if exist
        $newDamageFromType = $this->typeRepository->findOneBy(['slug' => $damageFromType]);

        $typeDamageFromType = $this->typeDamageFromTypeRepository->findBy([
            'type' => $type->getId(),
            'damageFromType' => $newDamageFromType->getId()
        ]);

        if (isset($typeDamageFromType)) {
            $typeDamageFromType = new TypeDamageFromType();

            $typeDamageFromType->setType($type);
            $typeDamageFromType->setDamageFromType($newDamageFromType);
            $typeDamageFromType->setCoefFrom(intval($coef));
            $damageFromTypeSlug = $type->getSlug() . $newDamageFromType->getSlug();
            $typeDamageFromType->setSlug($damageFromTypeSlug);

            $this->em->persist($typeDamageFromType);
            $this->em->flush();
        }

        return $typeDamageFromType;
    }
}
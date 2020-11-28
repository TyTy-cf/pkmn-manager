<?php

namespace App\Manager\Infos\Type;

use App\Entity\Infos\Type\Type;
use App\Entity\Infos\Type\TypeDamageRelationType;
use App\Manager\Api\ApiManager;
use App\Repository\Infos\Type\TypeDamageRelationTypeRepository;
use App\Repository\Infos\Type\TypeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class TypeDamageRelationTypeManager
{
    /**
     * @var TypeDamageRelationTypeRepository
     */
    private TypeDamageRelationTypeRepository $typeDamageRelationTypeRepository;

    /**
     * @var TypeRepository
     */
    private TypeRepository $typeRepository;

    /**
     * @var EntityManagerInterface $entityManager
     */
    private EntityManagerInterface $entityManager;

    /**
     * @var ApiManager $apiManager
     */
    private ApiManager $apiManager;

    /**
     * TypeDamageFromTypeRepository constructor
     * @param TypeRepository $typeRepository
     * @param ApiManager $apiManager
     * @param EntityManagerInterface $em
     * @param TypeDamageRelationTypeRepository $typeDamageRelationTypeRepository
     */
    public function __construct(
        TypeRepository $typeRepository,
        ApiManager $apiManager,
        EntityManagerInterface $em,
        TypeDamageRelationTypeRepository $typeDamageRelationTypeRepository
    ) {
        $this->entityManager = $em;
        $this->apiManager = $apiManager;
        $this->typeRepository = $typeRepository;
        $this->typeDamageRelationTypeRepository = $typeDamageRelationTypeRepository;
    }

    /**
     * @param Type $type
     * @param string $lang
     * @throws TransportExceptionInterface
     * @throws NonUniqueResultException
     */
    public function createDamageFromType(Type $type, string $lang)
    {
        $urlDetailedType = $this->apiManager->getDetailed('https://pokeapi.co/api/v2/type/' . $type->getCodeApi() . '/')->toArray();
        // Set all damage relation from
        $this->iterateOverJson($type, $lang, $urlDetailedType,'double_damage_from', 'from', 2);
        $this->iterateOverJson($type, $lang, $urlDetailedType,'half_damage_from', 'from',0.5);
        $this->iterateOverJson($type, $lang, $urlDetailedType,'no_damage_from', 'from',0);
        // Set all damage relation to
        $this->iterateOverJson($type, $lang, $urlDetailedType,'double_damage_to', 'to',2);
        $this->iterateOverJson($type, $lang, $urlDetailedType,'half_damage_to', 'to',0.5);
        $this->iterateOverJson($type, $lang, $urlDetailedType,'no_damage_to', 'to', 0);
        // On est arrivé à la fin, on peut flush
        $this->entityManager->flush();
    }

    /**
     * @param Type $type
     * @param string $lang
     * @param array $urlDetailedType
     * @param string $field
     * @param float $coef
     * @throws NonUniqueResultException
     */
    public function iterateOverJson(Type $type, string $lang, array $urlDetailedType, string $field, string $damageRelation, float $coef)
    {
        if (!empty($urlDetailedType['damage_relations'][$field]))
        {
            // Create the double_damage_from
            foreach($urlDetailedType['damage_relations'][$field] as $jsonType)
            {
                // Find the type impact in _from or _to
                $typeFrom = $this->typeRepository->getTypeByLanguageAndCodeApi($lang, $this->apiManager->getIdFromUrl($jsonType['url']));
                if ($typeFrom != null)
                {
                    $this->createTypeDamageRelationType($type, $typeFrom, $damageRelation, $coef);
                }
            }
        }
    }

    /**
     * @param Type $type
     * @param Type $typeFrom
     * @param string $damageRelation
     * @param float $coef
     */
    public function createTypeDamageRelationType(Type $type, Type $typeFrom, string $damageRelation, float $coef)
    {
        $typeDamageFromType = new TypeDamageRelationType();
        $typeDamageFromType->setType($type);
        $typeDamageFromType->setDamageRelationType($typeFrom);
        $typeDamageFromType->setDamageRelationCoefficient($coef);
        $typeDamageFromType->setDamageRelation($damageRelation);
        $damageFromTypeSlug = $type->getSlug() . '-' . $damageRelation . '-' . $typeFrom->getSlug();
        $typeDamageFromType->setSlug($damageFromTypeSlug);
        $this->entityManager->persist($typeDamageFromType);
    }
}
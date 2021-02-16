<?php

namespace App\Service\Infos\Type;

use App\Entity\Infos\Type\Type;
use App\Entity\Infos\Type\TypeDamageRelationType;
use App\Service\AbstractService;
use App\Service\Api\ApiService;
use App\Service\TextService;
use App\Repository\Infos\Type\TypeDamageRelationTypeRepository;
use App\Repository\Infos\Type\TypeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class TypeDamageRelationTypeService extends AbstractService
{

    /**
     * @var TypeRepository
     */
    private TypeRepository $typeRepository;

    /**
     * @var TypeDamageRelationTypeRepository
     */
    private TypeDamageRelationTypeRepository $typeDamageRelationTypeRepository;

    /**
     * TypeDamageFromTypeRepository constructor
     * @param TypeRepository $typeRepository
     * @param ApiService $apiManager
     * @param EntityManagerInterface $em
     * @param TextService $textManager
     * @param TypeDamageRelationTypeRepository $typeDamageRelationTypeRepository
     */
    public function __construct
    (
        TypeRepository $typeRepository,
        ApiService $apiManager,
        EntityManagerInterface $em,
        TextService $textManager,
        TypeDamageRelationTypeRepository $typeDamageRelationTypeRepository
    ) {
        $this->typeRepository = $typeRepository;
        $this->typeDamageRelationTypeRepository = $typeDamageRelationTypeRepository;
        parent::__construct($em, $apiManager, $textManager);
    }

    /**
     * @param Type $type
     * @return int|mixed|string
     */
    public function getRelationTypeByType(Type $type)
    {
        return $this->typeDamageRelationTypeRepository->getRelationTypeByType($type);
    }

    /**
     * @param Type $type
     * @param string $lang
     * @throws TransportExceptionInterface
     * @throws NonUniqueResultException
     */
    public function createDamageFromType(Type $type, string $lang)
    {
        $urlDetailedType = $this->apiManager->apiConnect('https://pokeapi.co/api/v2/type/' . $type->getCodeApi() . '/')->toArray();
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
     * @param string $damageRelation
     * @param float $coef
     * @throws NonUniqueResultException
     */
    public function iterateOverJson
    (
        Type $type,
        string $lang,
        array $urlDetailedType,
        string $field,
        string $damageRelation,
        float $coef
    ) {
        if (!empty($urlDetailedType['damage_relations'][$field]))
        {
            // Create the double_damage_from
            foreach($urlDetailedType['damage_relations'][$field] as $jsonType)
            {
                // Find the type impact in _from or _to
                $typeFrom = $this->typeRepository->getTypeByLanguageAndCodeApi(
                    $lang,
                    $this->apiManager->getIdFromUrl($jsonType['url'])
                );
                if (null !== $typeFrom)
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
        $damageFromTypeSlug = $type->getSlug() . '-' . $damageRelation . '-' . substr($typeFrom->getSlug(), 3);
        $typeDamageFromType = (new TypeDamageRelationType())
            ->setType($type)
            ->setDamageRelationType($typeFrom)
            ->setDamageRelationCoefficient($coef)
            ->setDamageRelation($damageRelation)
            ->setSlug($damageFromTypeSlug)
        ;
        $this->entityManager->persist($typeDamageFromType);
    }
}
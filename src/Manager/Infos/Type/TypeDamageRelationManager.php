<?php

namespace App\Manager\Infos\Type;

use App\Entity\Infos\Type\Type;
use App\Entity\Infos\Type\TypeDamageFromType;
use App\Entity\Infos\Type\TypeDamageToType;
use App\Manager\Api\ApiManager;
use App\Repository\Infos\Type\TypeDamageFromTypeRepository;
use App\Repository\Infos\Type\TypeDamageToTypeRepository;
use App\Repository\Infos\Type\TypeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class TypeDamageRelationManager
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
     * @var EntityManagerInterface $entityManager
     */
    private EntityManagerInterface $entityManager;

    /**
     * @var ApiManager $apiManager
     */
    private ApiManager $apiManager;

    /**
     * @var TypeDamageToTypeRepository $typeDamageToTypeRepository
     */
    private $typeDamageToTypeRepository;

    /**
     * TypeDamageFromTypeRepository constructor
     * @param TypeRepository $typeRepository
     * @param ApiManager $apiManager
     * @param EntityManagerInterface $em
     */
    public function __construct(
        TypeRepository $typeRepository,
        ApiManager $apiManager,
        EntityManagerInterface $em
    ) {
        $this->entityManager = $em;
        $this->apiManager = $apiManager;
        $this->typeRepository = $typeRepository;
        $this->typeDamageFromTypeRepository = $this->entityManager->getRepository(TypeDamageFromType::class);
        $this->typeDamageToTypeRepository = $this->entityManager->getRepository(TypeDamageToType::class);
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
        $this->iterateOverJson($type, $lang, $urlDetailedType,'double_damage_from', 2);
        $this->iterateOverJson($type, $lang, $urlDetailedType,'half_damage_from', 0.5);
        $this->iterateOverJson($type, $lang, $urlDetailedType,'no_damage_from', 0);
        // Set all damage relation to
        $this->iterateOverJson($type, $lang, $urlDetailedType,'double_damage_to', 2);
        $this->iterateOverJson($type, $lang, $urlDetailedType,'half_damage_to', 0.5);
        $this->iterateOverJson($type, $lang, $urlDetailedType,'no_damage_to', 0);
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
    public function iterateOverJson(Type $type, string $lang, array $urlDetailedType, string $field, float $coef)
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
                    // if the required field contains 'from' we create a TypeDamageFromType otherwise a TypeDamageToType
                    if (strpos($field, 'from')) {
                        $this->createDamageFromTypeWithCoef($type, $typeFrom, $coef);
                    } else {
                        $this->createDamageToTypeWithCoef($type, $typeFrom, $coef);
                    }
                }
            }
        }
    }

    /**
     * @param Type $type
     * @param Type $typeFrom
     * @param float $coef
     */
    public function createDamageFromTypeWithCoef(Type $type, Type $typeFrom, float $coef)
    {
        $typeDamageFromType = new TypeDamageFromType();
        $typeDamageFromType->setType($type);
        $typeDamageFromType->setDamageFromType($typeFrom);
        $typeDamageFromType->setCoefFrom($coef);
        $damageFromTypeSlug = $type->getSlug() . '-' . $typeFrom->getSlug();
        $typeDamageFromType->setSlug($damageFromTypeSlug);
        $this->entityManager->persist($typeDamageFromType);
    }

    /**
     * @param Type $type
     * @param Type $typeFrom
     * @param float $coef
     */
    private function createDamageToTypeWithCoef(Type $type, Type $typeFrom, float $coef)
    {
        $typeDamageFromType = new TypeDamageToType();
        $typeDamageFromType->setType($type);
        $typeDamageFromType->setDamageToType($typeFrom);
        $typeDamageFromType->setCoefTo($coef);
        $damageFromTypeSlug = $type->getSlug() . '-' . $typeFrom->getSlug();
        $typeDamageFromType->setSlug($damageFromTypeSlug);
        $this->entityManager->persist($typeDamageFromType);
    }
}
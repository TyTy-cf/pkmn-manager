<?php


namespace App\Service\Infos\Type;


use App\Entity\Infos\Type\Type;
use App\Entity\Infos\Type\TypeDamageRelationType;
use App\Entity\Pokemon\Pokemon;
use App\Entity\Users\Language;
use App\Service\AbstractService;
use App\Service\Api\ApiService;
use App\Service\TextService;
use App\Repository\Infos\Type\TypeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

/**
 * Class TypeService
 * @package App\Service\Infos\Type
 *
 * @property TypeDamageRelationTypeService $typeDamageFromTypeManager
 * @property TypeRepository $typeRepository
 */
class TypeService extends AbstractService
{

    /**
     * PokemonService constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param ApiService $apiService
     * @param TypeDamageRelationTypeService $typeDamageFromTypeService
     * @param TextService $textService
     * @param TypeRepository $typeRepository
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        ApiService $apiService,
        TypeDamageRelationTypeService $typeDamageFromTypeService,
        TextService $textService,
        TypeRepository $typeRepository
    ) {
        $this->typeDamageFromTypeManager = $typeDamageFromTypeService;
        $this->typeRepository = $typeRepository;
        parent::__construct($entityManager, $apiService, $textService);

    }

    /**
     * Return all Type based on a Language
     *
     * @param string $lang
     * @return Type[]|object[]
     */
    public function getAllTypeByLanguage(string $lang): array {
        return $this->typeRepository->getAllTypeByLanguage($lang);
    }

    /**
     * If not exist, save Type in Database according in language
     * @param Language $language
     * @param mixed $type
     * @throws TransportExceptionInterface
     * @throws NonUniqueResultException
     */
    public function createFromApiResponse(Language $language, $type)
    {
        if ($type['name'] !== "shadow" && $type['name'] !== "unknown") {
            $urlType = $type['url'];
            $codeLanguage = $language->getCode();
            $typeNameLang = $this->apiService->getNameBasedOnLanguage($codeLanguage, $urlType);
            $slug = $this->textService->slugify($typeNameLang);

            if (null === $newType = $this->typeRepository->findOneBySlugAndLanguage($slug, $codeLanguage)) {
                $newType = new Type();
            }

            if ($newType !== null) {
                //Fetch URL details type
                //Fetch name according the language
                $codeApi = $this->apiService->getIdFromUrl($urlType);

                //If database is null, create type
                $urlImg = '/images/types/' . $codeLanguage . '/' . strtolower($this->apiService->getNameBasedOnLanguage('en', $urlType));
                //Create new object and save in databases
                $newType->setName($typeNameLang);
                $newType->setSlug($slug);
                $newType->setLanguage($language);
                $newType->setImg($urlImg . '.png');
                $newType->setCodeApi($codeApi);
                $this->entityManager->persist($newType);
            }
        }
    }

    /**
     * @param Pokemon $pokemon
     * @return mixed
     */
    public function getTypesWeaknessesByPokemon(Pokemon $pokemon): array
    {
        $allTypes = $this->typeRepository->getAllTypesByLanguage($pokemon->getLanguage());
        $typesRelation = [];
        foreach($pokemon->getTypes() as $type) {
            $typesRelation[] = $this->typeDamageFromTypeManager->getRelationTypeByTypeAndRelationName($type, 'from');
        }
        $returnedTypes = [];
        foreach($allTypes as $type) {
            /** @var Type $type */
            $returnedTypes[$type->getName()] = 1;
            foreach ($typesRelation as $typeRelation) {
                foreach ($typeRelation as $otherType) {
                    /** @var TypeDamageRelationType $otherType */
                    if ($type === $otherType->getDamageRelationType()) {
                        $returnedTypes[$type->getName()] *= $otherType->getDamageRelationCoefficient();
                    }
                }
            }
        }
        return $returnedTypes;
    }
}

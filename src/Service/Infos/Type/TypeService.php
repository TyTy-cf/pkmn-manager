<?php


namespace App\Service\Infos\Type;


use App\Entity\Infos\Type\Type;
use App\Entity\Users\Language;
use App\Service\AbstractService;
use App\Service\Api\ApiService;
use App\Service\TextService;
use App\Repository\Infos\Type\TypeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class TypeService extends AbstractService
{

    /**
     * @var TypeDamageRelationTypeService $typeDamageFromTypeManager
     */
    private TypeDamageRelationTypeService $typeDamageFromTypeManager;

    /**
     * @var TypeRepository
     */
    private TypeRepository $typeRepository;

    /**
     * PokemonService constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param ApiService $apiManager
     * @param TypeDamageRelationTypeService $typeDamageFromTypeManager
     * @param TextService $textManager
     * @param TypeRepository $typeRepository
     */
    public function __construct
    (
        EntityManagerInterface $entityManager,
        ApiService $apiManager,
        TypeDamageRelationTypeService $typeDamageFromTypeManager,
        TextService $textManager,
        TypeRepository $typeRepository
    ) {
        $this->typeDamageFromTypeManager = $typeDamageFromTypeManager;
        $this->typeRepository = $typeRepository;
        parent::__construct($entityManager, $apiManager, $textManager);

    }

    /**
     * Return all Type based on a Language
     *
     * @param string $lang
     * @return Type[]|object[]
     */
    public function getAllTypeByLanguage(string $lang)
    {
        return $this->typeRepository->getAllTypeByLanguage($lang);
    }

    /**
     * Return all Type based on a Language
     *
     * @param Language $language
     * @return Type[]|object[]
     */
    public function getAllTypesByLanguage(Language $language)
    {
        return $this->typeRepository->getAllTypesByLanguage($language);
    }

    /**
     * @param string $slug
     * @return Type|null
     */
    public function getTypeBySlug(string $slug): ?Type
    {
        return $this->typeRepository->findOneBySlug($slug);
    }

    /**
     * If not exist, save Type in Database according in language
     * @param Language $language
     * @param mixed $type
     * @throws TransportExceptionInterface
     */
    public function createFromApiResponse(Language $language, $type)
    {
        $slug = $this->textManager->generateSlugFromClassWithLanguage(
            $language, Type::class, $type['name']
        );

        if (null === $this->typeRepository->findOneBySlug($slug))
        {
            //Fetch URL details type
            $urlType = $type['url'];

            //Fetch name according the language
            $typeNameLang = $this->apiManager->getNameBasedOnLanguage($language->getCode(), $urlType);
            $codeApi = $this->apiManager->getIdFromUrl($urlType);

            //Check if the data exist in databases
            $newType = $this->typeRepository->findOneBy(['name' => $typeNameLang]);

            //If database is null, create type
            if (empty($newType) && $type['name'] !== "shadow" && $type['name'] !== "unknown") {
                $urlImg = '/images/types/' . $language->getCode() . '/';
                //Create new object and save in databases
                $newType = new Type();
                $newType->setName($typeNameLang);
                $newType->setSlug($slug);
                $newType->setLanguage($language);
                $newType->setImg($urlImg . $slug . '.png');
                $newType->setCodeApi($codeApi);
                $this->entityManager->persist($newType);
                $this->entityManager->flush();
            }
        }
    }
}
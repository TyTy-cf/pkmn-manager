<?php


namespace App\Manager\Infos\Type;


use App\Entity\Infos\Type\Type;
use App\Entity\Pokemon\Pokemon;
use App\Entity\Users\Language;
use App\Manager\Api\ApiManager;
use App\Manager\Users\LanguageManager;
use App\Repository\Infos\Type\TypeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class TypeManager
{
    /**
     * @var TypeRepository $typeRepository
     */
    private TypeRepository $typeRepository;

    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;

    /**
     * @var ApiManager
     */
    private ApiManager $apiManager;

    /**
     * @var LanguageManager
     */
    private LanguageManager $languageManager;

    /**
     * @var TypeDamageRelationTypeManager $typeDamageFromTypeManager
     */
    private TypeDamageRelationTypeManager $typeDamageFromTypeManager;

    /**
     * PokemonManager constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param ApiManager $apiManager
     * @param LanguageManager $languageManager
     * @param TypeDamageRelationTypeManager $typeDamageFromTypeManager
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        ApiManager $apiManager,
        LanguageManager $languageManager,
        TypeDamageRelationTypeManager $typeDamageFromTypeManager
    ) {
        $this->entityManager = $entityManager;
        $this->apiManager = $apiManager;
        $this->languageManager = $languageManager;
        $this->typeDamageFromTypeManager = $typeDamageFromTypeManager;
        $this->typeRepository = $this->entityManager->getRepository(Type::class);
    }

    /**
     * If not exist, save Type in Database according in language
     * @param string $lang
     * @param mixed $type
     * @throws TransportExceptionInterface
     */
    public function createTypeIfNotExist(string $lang, $type)
    {
        //Fetch URL details type
        $urlType = $type['url'];

        //Fetch name according the language
        $typeNameLang = $this->apiManager->getNameBasedOnLanguage($lang, $urlType);
        $codeApi = $this->apiManager->getIdFromUrl($urlType);

        //Check if the data exist in databases
        $newType = $this->typeRepository->findOneBy(['name' => $typeNameLang]);

        //If database is null, create type
        if (empty($newType) && $type['name'] !== "shadow" && $type['name'] !== "unknown") {

            $urlImg = '/images/types/' . $lang . '/';
            $language = $this->languageManager->getLanguageByCode($lang);

            //Create new object and save in databases
            $newType = new Type();
            $newType->setName($typeNameLang);
            $newType->setSlug(mb_strtolower('type-' . $type['name']));
            $newType->setLanguage($language);
            $newType->setImg($urlImg . $type['name'] . '.png');
            $newType->setCodeApi($codeApi);
            $this->entityManager->persist($newType);
            $this->entityManager->flush();
        }
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
     * @param Language $language
     * @param string $string
     */
    public function getTypeByLanguageAndSlug(Language $language, string $slug)
    {
        return $this->typeRepository->getTypeByLanguageAndSlug($language, $slug);
    }
}
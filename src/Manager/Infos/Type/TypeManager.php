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
     * @var TypeRepository
     */
    private $typeRepository;

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
     * @var TypeDamageFromTypeManager $typeDamageFromTypeManager
     */
    private TypeDamageFromTypeManager $typeDamageFromTypeManager;

    /**
     * PokemonManager constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param ApiManager $apiManager
     * @param LanguageManager $languageManager
     * @param TypeDamageFromTypeManager $typeDamageFromTypeManager
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        ApiManager $apiManager,
        LanguageManager $languageManager,
        TypeDamageFromTypeManager $typeDamageFromTypeManager
    ) {
        $this->entityManager = $entityManager;
        $this->apiManager = $apiManager;
        $this->languageManager = $languageManager;
        $this->typeDamageFromTypeManager = $typeDamageFromTypeManager;
        $this->typeRepository = $this->entityManager->getRepository(Type::class);
    }

    /**
     * Check if the types exists. If it does not exist, create the type in database.
     * @param string $lang
     * @param $types
     * @return Object
     * @throws TransportExceptionInterface
     */
    public function saveNewTypesByPkmn(string $lang, $types)
    {
        //Check if language exist else create language
        $language = $this->languageManager->createLanguage($lang);

        // Iterate the types from the json, create the type if not existing or get it
        foreach ($types as $type) {

            $urlType = $type['type']['url'];
            $typeName = $this->getNameBasedOnLanguage($lang, $urlType);
            $typeNameEn = $type['type']['name'];

            if (($newType = $this->typeRepository->findOneBy(['name' => $typeName])) == null) {
                $urlImg = '/images/types/' . $language->getCode() . '/';
                $newType = new Type();
                $newType->setName($typeName);
                $newType->setSlug($typeNameEn);
                $newType->setLanguage($language);
                $newType->setImg($urlImg . $typeNameEn . '.png');
                $this->entityManager->persist($newType);
            }

            $this->entityManager->flush();
        }

        return $newType;
    }

    /**
     * If not exist, save Type in Database according in language
     * @param Language $language
     * @param mixed $type
     * @throws TransportExceptionInterface
     */
    public function createIfNotExist(Language $language, $type)
    {
        //Fetch URL details type
        $urlType = $type['url'];

        //Fetch name according the language
        $typeNameLang = $this->apiManager->getNameBasedOnLanguage($language->getCode(), $urlType);

        //Check if the data exist in databases
        $newType = $this->typeRepository->findOneBy(['name' => $typeNameLang]);

        //If database is null, create type
        if (empty($newType) && $type['name'] !== "shadow" && $type['name'] !== "unknown") {

            $urlImg = '/images/types/' . $language->getCode() . '/';

            //Create new object and save in databases
            $newType = new Type();
            $newType->setName($typeNameLang);
            $newType->setSlug(mb_strtolower('type-' . $type['name']));
            $newType->setLanguage($language);
            $newType->setImg($urlImg . $type['name'] . '.png');
            $this->entityManager->persist($newType);
            $this->entityManager->flush();
        }
    }
}
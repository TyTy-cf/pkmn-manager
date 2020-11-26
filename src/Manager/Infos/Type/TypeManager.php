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
     * PokemonManager constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param ApiManager $apiManager
     */
    public function __construct(EntityManagerInterface $entityManager, ApiManager $apiManager, LanguageManager $languageManager)
    {
        $this->entityManager = $entityManager;
        $this->apiManager = $apiManager;
        $this->languageManager = $languageManager;
        $this->typeRepository = $this->entityManager->getRepository(Type::class);
    }

    /**
     * Check if the types exists. If it does not exist, create the type in database.
     * @param string $lang
     * @param $types
     * @return Object
     * @throws TransportExceptionInterface
     */
    public function saveNewTypes(string $lang, $types)
    {
        //Check if language exist else create language
        $language = $this->languageManager->createLanguage($lang);

        // Iterate the types from the json, create the type if not existing or get it
        foreach ($types as $type) {

            $urlType = $type['type']['url'];
            $typeName = $this->getTypesInformationOnLanguage($lang, $urlType);
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
     * @param $lang
     * @param $url
     * @return mixed
     * @throws TransportExceptionInterface
     */
    public function getTypesInformationOnLanguage($lang, $url)
    {
        $apiResponse = $this->apiManager->getDetailed($url)->toArray();
        $typeName = null;

        foreach ($apiResponse['names'] as $name) {
            if ($name['language']['name'] === $lang) {
                $typeName = $name['name'];
            }
        }

        return $typeName;
    }
}
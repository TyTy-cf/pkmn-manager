<?php


namespace App\Manager\Infos\Type;


use App\Entity\Infos\Type\Type;
use App\Entity\Users\Language;
use App\Manager\AbstractManager;
use App\Manager\Api\ApiManager;
use App\Manager\TextManager;
use App\Repository\Infos\Type\TypeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class TypeManager extends AbstractManager
{

    /**
     * @var TypeDamageRelationTypeManager $typeDamageFromTypeManager
     */
    private TypeDamageRelationTypeManager $typeDamageFromTypeManager;

    /**
     * @var TypeRepository
     */
    private TypeRepository $typeRepository;

    /**
     * PokemonManager constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param ApiManager $apiManager
     * @param TypeDamageRelationTypeManager $typeDamageFromTypeManager
     * @param TextManager $textManager
     * @param TypeRepository $typeRepository
     */
    public function __construct
    (
        EntityManagerInterface $entityManager,
        ApiManager $apiManager,
        TypeDamageRelationTypeManager $typeDamageFromTypeManager,
        TextManager $textManager,
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
     * @param Type $type
     * @return Type[]|object[]
     */
    public function getAllOtherTypeByType(Type $type)
    {
        return $this->typeRepository->getAllOtherTypeByType($type);
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

        if ($this->getTypeBySlug($slug) === null)
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
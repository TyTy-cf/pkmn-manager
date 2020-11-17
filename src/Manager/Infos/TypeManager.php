<?php


namespace App\Manager\Infos;


use App\Entity\Infos\Type;
use App\Entity\Pokemon\Pokemon;
use App\Manager\Api\ApiManager;
use App\Repository\Infos\TypeRepository;
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
    private $entityManager;

    /**
     * @var ApiManager
     */
    private $apiManager;

    /**
     * PokemonManager constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param ApiManager $apiManager
     */
    public function __construct(EntityManagerInterface $entityManager, ApiManager $apiManager)
    {
        $this->entityManager = $entityManager;
        $this->apiManager = $apiManager;
        $this->typeRepository = $this->entityManager->getRepository(Type::class);
    }

    /**
     * @param $types
     * @param Pokemon $pokemon
     * @throws TransportExceptionInterface
     */
    public function saveNewTypes($types, Pokemon $pokemon)
    {
        // Iterate the types from the json, create the type if not existing or get it
        foreach ($types as $type) {

            $typeNameEn = $type['type']['name'];
            if (($newType = $this->typeRepository->findOneBy(['nameEn' => $typeNameEn])) == null) {

                $urlType = $type['type']['url'];

                $typeNameFr = $this->getTypesInformationsOnLanguage('fr', $urlType);

                $newType = new Type();
                $newType->setNameEn(ucfirst($typeNameEn));
                $newType->setNameFr(ucfirst($typeNameFr));
                $this->entityManager->persist($newType);
            }
            $pokemon->addType($newType);
            $this->entityManager->flush();
        }
    }

    /**
     * @param $lang
     * @param $url
     * @return mixed
     * @throws TransportExceptionInterface
     */
    public function getTypesInformationsOnLanguage($lang, $url)
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
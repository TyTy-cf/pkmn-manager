<?php


namespace App\Manager\Infos;


use App\Entity\Infos\Type;
use App\Entity\Pokemon\Pokemon;
use App\Kernel;
use App\Manager\Api\ApiManager;
use App\Repository\Infos\TypeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpKernel\KernelInterface;
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
     * @param KernelInterface $kernel
     */
    public function __construct(EntityManagerInterface $entityManager, ApiManager $apiManager, KernelInterface $kernel)
    {
        $this->entityManager = $entityManager;
        $this->apiManager = $apiManager;
        $this->kernel = $kernel;
        $this->typeRepository = $this->entityManager->getRepository(Type::class);
    }

    /**
     * @param $lang
     * @param $types
     * @param Pokemon $pokemon
     * @throws TransportExceptionInterface
     */
    public function saveNewTypes($lang, $types, Pokemon $pokemon)
    {
        // Iterate the types from the json, create the type if not existing or get it
        foreach ($types as $type) {

            $urlType = $type['type']['url'];
            $typeName = $this->getTypesInformationsOnLanguage($lang, $urlType);
            $typeNameEn = $type['type']['name'];

            if (($newType = $this->typeRepository->findOneBy(['name' => $typeName])) == null) {

                $urlImg = '/images/types/';

                $newType = new Type();
                $newType->setName($typeName);

//                $newType->setNameEn(ucfirst($typeNameEn));
//                $newType->setNameFr(ucfirst($typeNameFr));
                $newType->setImg($urlImg . $typeNameEn . '.png');

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
<?php


namespace App\Manager\Pokedex;


use App\Entity\Pokedex\EggGroup;
use App\Entity\Users\Language;
use App\Manager\AbstractManager;
use App\Manager\Api\ApiManager;
use App\Manager\TextManager;
use App\Repository\Pokedex\EggGroupRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class EggGroupManager extends AbstractManager
{
    /**
     * @var EggGroupRepository
     */
    private EggGroupRepository $eggGroupRepository;

    /**
     * PokemonManager constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param EggGroupRepository $eggGroupRepository
     * @param ApiManager $apiManager
     * @param TextManager $textManager
     */
    public function __construct
    (
        EntityManagerInterface $entityManager,
        EggGroupRepository $eggGroupRepository,
        ApiManager $apiManager,
        TextManager $textManager
    )
    {
        $this->eggGroupRepository = $eggGroupRepository;
        parent::__construct($entityManager, $apiManager, $textManager);
    }

    /**
     * @param string $slug
     * @return EggGroup|null
     */
    public function getEggGroupBySlug(string $slug)
    {
        return $this->eggGroupRepository->findOneBySlug($slug);
    }

    /**
     * @param Language $language
     * @param $apiEggGroup
     * @throws TransportExceptionInterface
     */
    public function createFromApiResponse(Language $language, $apiEggGroup)
    {
        //Check if the data exist in databases
        $slug = $this->textManager->generateSlugFromClassWithLanguage(
            $language, EggGroup::class, $apiEggGroup['name']
        );
        if ($this->getEggGroupBySlug($slug) === null)
        {
            //Fetch URL details type
            $urlDamageClassDetailed = $this->apiManager->getDetailed($apiEggGroup['url'])->toArray();
            // Fetch name & description according the language
            $eggGroupName = $this->apiManager->getNameBasedOnLanguageFromArray(
                $language->getCode(),
                $urlDamageClassDetailed
            );
            $eggGroup = (new EggGroup())
                ->setName(ucfirst($eggGroupName))
                ->setSlug($slug)
                ->setLanguage($language)
            ;
            $this->entityManager->persist($eggGroup);
            $this->entityManager->flush();
        }
    }

}
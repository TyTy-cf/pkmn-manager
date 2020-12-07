<?php


namespace App\Manager\Versions;


use App\Entity\Users\Language;
use App\Entity\Versions\Generation;
use App\Manager\AbstractManager;
use App\Manager\Api\ApiManager;
use App\Manager\TextManager;
use App\Repository\Versions\GenerationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class GenerationManager extends AbstractManager
{
    /**
     * @var GenerationRepository
     */
    private GenerationRepository $generationRepository;

    /**
     * PokemonManager constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param ApiManager $apiManager
     * @param TextManager $textManager
     * @param GenerationRepository $generationRepository
     */
    public function __construct
    (
        EntityManagerInterface $entityManager,
        ApiManager $apiManager,
        TextManager $textManager,
        GenerationRepository $generationRepository
    )
    {
        $this->generationRepository = $generationRepository;
        parent::__construct($entityManager, $apiManager, $textManager);
    }

    /**
     * @param string $slug
     * @return Generation|null
     */
    public function getGenerationBySlug(string $slug): object
    {
        return $this->generationRepository->findOneBySlug($slug);
    }

    /**
     * @param Language $language
     * @param $generation
     * @throws TransportExceptionInterface
     */
    public function createFromApiResponse(Language $language, $generation)
    {
        //Check if the data exist in databases
        $slug = $this->textManager->generateSlugFromClassWithLanguage(
            $language, Generation::class, $generation['name']
        );

        if ($this->getGenerationBySlug($slug) === null)
        {
            //Fetch URL details type
            $urlDetailed = $this->apiManager->getDetailed($generation['url'])->toArray();

            if ($this->getGenerationBySlug($slug) === null)
            {
                // Fetch name & description according the language
                $generationLang = $this->apiManager->getNameBasedOnLanguageFromArray(
                    $language->getCode(), $urlDetailed
                );
                $splittedGeneration = explode(' ', $generationLang);

                $newGeneration = (new Generation())
                    ->setSlug($slug)
                    ->setCode(Generation::$relationArray[$urlDetailed['id']])
                    ->setLanguage($language)
                    ->setNumber($urlDetailed['id'])
                    ->setName($splittedGeneration[0] . ' ' . $urlDetailed['id'])
                ;
                $this->entityManager->persist($newGeneration);
                $this->entityManager->flush();
            }
        }
    }

}



















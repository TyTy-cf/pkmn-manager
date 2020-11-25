<?php

namespace App\Manager\Users;

use App\Entity\Users\Language;
use App\Manager\Api\ApiManager;
use App\Repository\Users\LanguageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class LanguageManager
{
    /**
     * @var LanguageRepository
     */
    private LanguageRepository $languageRepository;

    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $em;

    /**
     * @var ApiManager
     */
    private ApiManager $apiManager;

    public function __construct(
        EntityManagerInterface $em,
        ApiManager $apiManager,
        LanguageRepository $languageRepository
    ) {
        $this->em = $em;
        $this->apiManager = $apiManager;
        $this->languageRepository = $languageRepository;
    }

    /**
     * Check if the language exists. If it does not exist, create the language in database
     * @param string $lang
     * @throws TransportExceptionInterface
     */
    public function createLanguage(string $lang)
    {
        if (($newLanguage = $this->languageRepository->findOneBy(['code' => $lang])) === null) {

            $apiResponse = $this->apiManager->getDetailed("https://pokeapi.co/api/v2/language/");
            $languages = $apiResponse->toarray();

            foreach ($languages['results'] as $language) {

                if ($language['name'] === $lang) {

                    //Search more informations
                    $apiResponse = $this->apiManager->getDetailed($language['url']);
                    $detailedLanguages = $apiResponse->toarray();

                    //Match search
                    foreach ($detailedLanguages['names'] as $detailedLanguage) {

                        if ($detailedLanguage['language']['name'] === 'fr') {
                            //Create new language
                            $newLanguage = new Language();
                            $newLanguage->setCode($language['name']);
                            $newLanguage->setTitle($detailedLanguage['name']);

                            $this->em->persist($newLanguage);
                            $this->em->flush();
                        }
                    }
                }
            }
        }

        return $newLanguage;
    }
}
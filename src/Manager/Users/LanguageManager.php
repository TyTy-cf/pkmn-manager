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

    public function __construct
    (
        EntityManagerInterface $em,
        ApiManager $apiManager,
        LanguageRepository $languageRepository
    ) {
        $this->em = $em;
        $this->apiManager = $apiManager;
        $this->languageRepository = $languageRepository;
    }

    /**
     * Return a language based on the code
     *
     * @param string $code
     * @return Language|null
     */
    public function getLanguageByCode(string $code) {
        return $this->languageRepository->findOneBy(['code' => $code]);
    }
}
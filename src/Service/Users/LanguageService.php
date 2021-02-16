<?php

namespace App\Service\Users;

use App\Entity\Users\Language;
use App\Service\Api\ApiService;
use App\Repository\Users\LanguageRepository;
use Doctrine\ORM\EntityManagerInterface;

class LanguageService
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
     * @var ApiService
     */
    private ApiService $apiService;

    /**
     * @var Language|null $languageUsed
     */
    private static ?Language $languageUsed;

    /**
     * LanguageManager constructor.
     * @param EntityManagerInterface $em
     * @param ApiService $apiService
     * @param LanguageRepository $languageRepository
     */
    public function __construct
    (
        EntityManagerInterface $em,
        ApiService $apiService,
        LanguageRepository $languageRepository
    ) {
        $this->em = $em;
        self::$languageUsed = null;
        $this->apiService = $apiService;
        $this->languageRepository = $languageRepository;
    }

    /**
     * @param string $code
     * @return Language|null
     */
    public function getLanguageByCode(string $code): ?Language
    {
        if (self::$languageUsed === null) {
            self::$languageUsed =  $this->languageRepository->findOneBy(['code' => $code]);
        }
        return self::$languageUsed;
    }
}
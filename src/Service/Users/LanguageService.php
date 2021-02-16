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
    private ApiService $apiManager;

    /**
     * @var Language|null $languageUsed
     */
    private static ?Language $languageUsed;

    /**
     * LanguageManager constructor.
     * @param EntityManagerInterface $em
     * @param ApiService $apiManager
     * @param LanguageRepository $languageRepository
     */
    public function __construct
    (
        EntityManagerInterface $em,
        ApiService $apiManager,
        LanguageRepository $languageRepository
    ) {
        $this->em = $em;
        self::$languageUsed = null;
        $this->apiManager = $apiManager;
        $this->languageRepository = $languageRepository;
    }

    /**
     * @param string $code
     * @return Language|null
     */
    public function getLanguageByCode(string $code) {
        if (self::$languageUsed === null) {
            self::$languageUsed =  $this->languageRepository->findOneBy(['code' => $code]);
        }
        return self::$languageUsed;
    }
}
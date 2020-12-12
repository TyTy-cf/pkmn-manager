<?php

namespace App\Manager\Users;

use App\Entity\Users\Language;
use App\Manager\Api\ApiManager;
use App\Repository\Users\LanguageRepository;
use Doctrine\ORM\EntityManagerInterface;

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

    /**
     * @var Language|null $languageUsed
     */
    private static ?Language $languageUsed;

    /**
     * LanguageManager constructor.
     * @param EntityManagerInterface $em
     * @param ApiManager $apiManager
     * @param LanguageRepository $languageRepository
     */
    public function __construct
    (
        EntityManagerInterface $em,
        ApiManager $apiManager,
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
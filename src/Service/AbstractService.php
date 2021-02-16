<?php


namespace App\Service;


use App\Entity\Users\Language;
use App\Service\Api\ApiService;
use Doctrine\ORM\EntityManagerInterface;

class AbstractService
{

    /**
     * @var EntityManagerInterface
     */
    protected EntityManagerInterface $entityManager;

    /**
     * @var ApiService
     */
    protected ApiService $apiService;

    /**
     * @var TextService
     */
    protected TextService $textService;

    /**
     * AbstractService constructor.
     * @param EntityManagerInterface $entityManager
     * @param ApiService $apiService
     * @param TextService $textService
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        ApiService $apiService,
        TextService $textService
    )
    {
        $this->entityManager = $entityManager;
        $this->apiService = $apiService;
        $this->textService = $textService;
    }

    /**
     * @param Language $language
     * @param $apiResponse
     */
    public function createFromApiResponse(Language $language, $apiResponse)
    {

    }
}
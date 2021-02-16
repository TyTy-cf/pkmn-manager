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
    protected ApiService $apiManager;

    /**
     * @var TextService
     */
    protected TextService $textManager;

    /**
     * AbstractService constructor.
     * @param EntityManagerInterface $entityManager
     * @param ApiService $apiManager
     * @param TextService $textManager
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        ApiService $apiManager,
        TextService $textManager
    )
    {
        $this->entityManager = $entityManager;
        $this->apiManager = $apiManager;
        $this->textManager = $textManager;
    }

    /**
     * @param Language $language
     * @param $apiResponse
     */
    public function createFromApiResponse(Language $language, $apiResponse)
    {

    }
}
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
     * @var TextManager
     */
    protected TextManager $textManager;

    /**
     * AbstractService constructor.
     * @param EntityManagerInterface $entityManager
     * @param ApiService $apiManager
     * @param TextManager $textManager
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        ApiService $apiManager,
        TextManager $textManager
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
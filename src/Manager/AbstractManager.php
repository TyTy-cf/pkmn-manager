<?php


namespace App\Manager;


use App\Entity\Users\Language;
use App\Manager\Api\ApiManager;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;

class AbstractManager
{

    /**
     * @var EntityManagerInterface
     */
    protected EntityManagerInterface $entityManager;

    /**
     * @var ApiManager
     */
    protected ApiManager $apiManager;

    /**
     * @var TextManager
     */
    protected TextManager $textManager;

    /**
     * AbstractManager constructor.
     * @param EntityManagerInterface $entityManager
     * @param ApiManager $apiManager
     * @param TextManager $textManager
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        ApiManager $apiManager,
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
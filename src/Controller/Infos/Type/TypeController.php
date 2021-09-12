<?php


namespace App\Controller\Infos\Type;


use App\Repository\Infos\Type\TypeRepository;
use App\Service\Infos\Type\TypeDamageRelationTypeService;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TypeController extends AbstractController
{

    /**
     * Display the last pokemon add in the database
     *
     * @Route (path="{code}/type/{slug}", name="type_detail")
     *
     * @param TypeDamageRelationTypeService $typeRelationService
     * @param TypeRepository $typeRepository
     * @param string $code
     * @param string $slug
     * @return Response
     * @throws NonUniqueResultException
     */
    public function index(
        TypeDamageRelationTypeService $typeRelationService,
        TypeRepository $typeRepository,
        string $code,
        string $slug
    ): Response {
        $type = $typeRepository->findOneBySlugWithRelation($slug, $code);
        return $this->render('Type/detail.html.twig', [
            'type' => $type,
            'typeRelation' => $typeRelationService->getRelationTypeByType($type),
            'types' => $typeRepository->getAllTypesByLanguage($type->getLanguage()),
        ]);
    }
}

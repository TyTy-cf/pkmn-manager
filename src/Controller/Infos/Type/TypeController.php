<?php


namespace App\Controller\Infos\Type;


use App\Repository\Infos\Type\TypeRepository;
use App\Service\Infos\Type\TypeDamageRelationTypeService;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TypeController extends AbstractController
{

    /**
     * Display the last pokemon add in the database
     *
     * @Route (path="/type/{slug_type}", name="type_detail", requirements={"slug_type": ".+"})
     *
     * @param Request $request
     * @param TypeDamageRelationTypeService $typeRelationService
     * @param TypeRepository $typeRepository
     * @return Response
     * @throws NonUniqueResultException
     */
    public function index(
        Request $request,
        TypeDamageRelationTypeService $typeRelationService,
        TypeRepository $typeRepository
    ): Response {
        $type = $typeRepository->findOneBySlugWithRelation($request->get('slug_type'));
        return $this->render('Type/detail.html.twig', [
            'type' => $type,
            'typeRelation' => $typeRelationService->getRelationTypeByType($type),
            'types' => $typeRepository->getAllTypesByLanguage($type->getLanguage()),
        ]);
    }
}

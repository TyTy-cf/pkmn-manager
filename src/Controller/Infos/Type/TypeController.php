<?php


namespace App\Controller\Infos\Type;


use App\Entity\Infos\Type\Type;
use App\Repository\Infos\Type\TypeRepository;
use App\Service\Infos\Type\TypeDamageRelationTypeService;
use App\Service\Infos\Type\TypeService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TypeController extends AbstractController
{

    /**
     * @var TypeDamageRelationTypeService $typeRelationService
     */
    public TypeDamageRelationTypeService $typeRelationService;

    /**
     * @var TypeRepository $typeRepository
     */
    private TypeRepository $typeRepository;

    /**
     * TypeController constructor.
     * @param TypeDamageRelationTypeService $typeRelationService
     * @param TypeRepository $typeRepository
     */
    public function __construct(
        TypeDamageRelationTypeService $typeRelationService,
        TypeRepository $typeRepository
    ) {
        $this->typeRelationService = $typeRelationService;
        $this->typeRepository = $typeRepository;
    }

    /**
     * Display the last pokemon add in the database
     *
     * @Route (path="/type/{slug_type}", name="type_detail", requirements={"slug_type": ".+"})
     *
     * @param Request $request
     * @return Response
     */
    public function typeDetail(
        Request $request
    ): Response {
        $type = $this->typeRepository->findOneBySlugWithRelation($request->get('slug_type'));
        return $this->render('Type/detail.html.twig', [
            'type' => $type,
            'typeRelation' => $this->typeRelationService->getRelationTypeByType($type),
            'types' => $this->typeRepository->getAllTypesByLanguage($type->getLanguage()),
        ]);
    }
}

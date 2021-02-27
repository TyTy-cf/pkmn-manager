<?php


namespace App\Controller\Infos\Type;


use App\Entity\Infos\Type\Type;
use App\Service\Infos\Type\TypeDamageRelationTypeService;
use App\Service\Infos\Type\TypeService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TypeController extends AbstractController
{

    /**
     * @var TypeDamageRelationTypeService $typeRelationManager
     */
    public TypeDamageRelationTypeService $typeRelationManager;

    /**
     * @var TypeService $typeManager
     */
    private TypeService $typeManager;

    /**
     * TypeController constructor.
     * @param TypeDamageRelationTypeService $typeRelationManager
     * @param TypeService $typeManager
     */
    public function __construct(
        TypeDamageRelationTypeService $typeRelationManager,
        TypeService $typeManager
    ) {
        $this->typeRelationManager = $typeRelationManager;
        $this->typeManager = $typeManager;
    }

    /**
     * Display the last pokemon add in the database
     *
     * @Route (path="/type/{slug_type}", name="type_detail", requirements={"slug_type": ".+"})
     * @ParamConverter(class="App\Entity\Infos\Type\Type", name="type", options={"mapping": {"slug_type" : "slug"}})
     *
     * @param Type $type
     * @return Response
     */
    public function typeDetail(Type $type): Response {
        return $this->render('Type/detail.html.twig', [
            'type' => $type,
            'typeRelation' => $this->typeRelationManager->getRelationTypeByType($type),
            'types' => $this->typeManager->getAllTypesByLanguage($type->getLanguage()),
        ]);
    }
}

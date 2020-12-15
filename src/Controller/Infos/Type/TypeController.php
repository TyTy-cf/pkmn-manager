<?php


namespace App\Controller\Infos\Type;


use App\Entity\Infos\Type\Type;
use App\Manager\Infos\Type\TypeDamageRelationTypeManager;
use App\Manager\Infos\Type\TypeManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TypeController extends AbstractController
{

    /**
     * @var TypeDamageRelationTypeManager $typeRelationManager
     */
    public TypeDamageRelationTypeManager $typeRelationManager;

    /**
     * @var TypeManager $typeManager
     */
    private TypeManager $typeManager;

    /**
     * TypeController constructor.
     * @param TypeDamageRelationTypeManager $typeRelationManager
     * @param TypeManager $typeManager
     */
    public function __construct(
        TypeDamageRelationTypeManager $typeRelationManager,
        TypeManager $typeManager
    )
    {
        $this->typeRelationManager = $typeRelationManager;
        $this->typeManager = $typeManager;
    }

    /**
     * Display the last pokemon add in the database
     *
     * @Route (path="/type/{slug_type}", name="type_detail", requirements={"slug_type": ".+"})
     * @ParamConverter(class="App\Entity\Infos\Type\Type", name="type", options={"mapping": {"slug_type" : "slug"}})
     *
     * @param Request $request
     * @param Type $type
     * @return Response
     */
    public function typeDetail(Request $request, Type $type): Response {
        dump($this->typeRelationManager->getRelationTypeByType($type));
        dump($this->typeManager->getAllOtherTypeByType($type));
        return $this->render('Type/detail.html.twig', [
            'type' => $type,
            'typeRelation' => $this->typeRelationManager->getRelationTypeByType($type),
            'types' => $this->typeManager->getAllOtherTypeByType($type),
        ]);
    }
}

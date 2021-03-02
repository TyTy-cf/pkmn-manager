<?php


namespace App\Controller\Pokedex;


use App\Entity\Pokedex\EggGroup;
use App\Repository\Pokedex\EggGroupRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EggGroupController extends AbstractController
{
    /**
     * Display the last pokemon add in the database
     *
     * @Route (path="/egg_group/{slug_egg}", name="egg_group_detail", requirements={"slug_egg": ".+"})
     * @ParamConverter(class="App\Entity\Pokedex\EggGroup", name="eggGroup", options={"mapping": {"slug_egg" : "slug"}})
     *
     * @param Request $request
     * @param EggGroupRepository $eggGroupRepository
     * @return Response
     */
    public function abilityDetail(
        Request $request,
        EggGroupRepository $eggGroupRepository
    ): Response {
        return $this->render('EggGroup/detail.html.twig', [
            'eggGroup' => $eggGroupRepository->findOneBySlugWithRelation($request->get('slug_egg')),
        ]);
    }
}

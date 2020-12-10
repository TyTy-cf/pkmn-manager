<?php


namespace App\Controller\Pokedex;


use App\Manager\Pokedex\EggGroupManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EggGroupController extends AbstractController
{
    /**
     * Display the last pokemon add in the database
     *
     * @Route (path="/egg_group/{slug}", name="egg_group_detail", requirements={"slug": ".+"})
     * @param Request $request
     * @param EggGroupManager $eggGroupManager
     * @return Response
     */
    public function abilityDetail(
        Request $request,
        EggGroupManager $eggGroupManager
    ): Response {
        return $this->render('EggGroup/detail.html.twig', [
            'eggGroup' => $eggGroupManager->getEggGroupBySlug($request->get('slug')),
        ]);
    }
}

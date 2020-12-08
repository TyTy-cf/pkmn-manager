<?php


namespace App\Controller\Infos;


use App\Manager\Infos\AbilityManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AbilityController extends AbstractController
{
    /**
     * Display the last pokemon add in the database
     *
     * @Route (path="/ability/{slug}", name="ability_detail", requirements={"slug": ".+"})
     * @param Request $request
     * @param AbilityManager $abilityManager
     * @return Response
     */
    public function abilityDetail(
        Request $request,
        AbilityManager $abilityManager
    ): Response {
        return $this->render('Ability/detail.html.twig', [
            'ability' => $abilityManager->getAbilitiesBySlug($request->get('slug')),
        ]);
    }
}

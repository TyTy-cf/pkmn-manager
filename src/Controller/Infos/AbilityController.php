<?php


namespace App\Controller\Infos;


use App\Entity\Infos\Ability;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AbilityController extends AbstractController
{

    /**
     * Display the last pokemon add in the database
     *
     * @Route (path="/ability/{slug_ability}", name="ability_detail", requirements={"slug_ability": ".+"})
     * @ParamConverter(class="App\Entity\Infos\Ability", name="ability", options={"mapping": {"slug_ability" : "slug"}})
     *
     * @param Request $request
     * @param Ability $ability
     * @return Response
     */
    public function abilityDetail(Request $request, Ability $ability): Response {
        return $this->render('Ability/detail.html.twig', [
            'ability' => $ability,
        ]);
    }

}

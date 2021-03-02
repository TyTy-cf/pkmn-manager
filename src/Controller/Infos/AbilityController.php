<?php


namespace App\Controller\Infos;


use App\Repository\Infos\AbilityRepository;
use App\Repository\Infos\AbilityVersionGroupRepository;
use Doctrine\ORM\NonUniqueResultException;
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
     *
     * @param Request $request
     * @param AbilityRepository $abilityRepository
     * @param AbilityVersionGroupRepository $abilityVersionGroupRepository
     * @return Response
     * @throws NonUniqueResultException
     */
    public function index(
        Request $request,
        AbilityRepository $abilityRepository,
        AbilityVersionGroupRepository $abilityVersionGroupRepository
    ): Response {
        return $this->render('Ability/detail.html.twig', [
            'ability' => $abilityRepository->findOneBySlugWithRelation($request->get('slug_ability')),
            'abilityVersionGroup' => $abilityVersionGroupRepository->findAbilityVersionGroupBySlug($request->get('slug_ability')),
        ]);
    }

}

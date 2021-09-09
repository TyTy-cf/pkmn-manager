<?php


namespace App\Controller\Infos;


use App\Form\Filters\AbilityFormFilterType;
use App\Form\Filters\OrderCollectionFilterType;
use App\Repository\Infos\AbilityRepository;
use App\Repository\Infos\AbilityVersionGroupRepository;
use App\Repository\Users\LanguageRepository;
use Doctrine\ORM\NonUniqueResultException;
use Knp\Component\Pager\PaginatorInterface;
use Lexik\Bundle\FormFilterBundle\Filter\FilterBuilderUpdaterInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AbilityController
 * @package App\Controller\Infos
 *
 * @property AbilityRepository $abilityRepository
 * @property LanguageRepository $languageRepository
 */
class AbilityController extends AbstractController
{

    /**
     * AbilityController constructor.
     * @param AbilityRepository $abilityRepository
     * @param LanguageRepository $languageRepository
     */
    public function __construct(AbilityRepository $abilityRepository, LanguageRepository $languageRepository)
    {
        $this->abilityRepository = $abilityRepository;
        $this->languageRepository = $languageRepository;
    }

    /**
     * Display the detailed ability and related pokemons with this ability
     *
     * @Route (path="/talents/{slug_ability}", name="ability_show", requirements={"slug_ability": ".+"})
     *
     * @param Request $request
     * @param AbilityVersionGroupRepository $abilityVersionGroupRepository
     * @return Response
     * @throws NonUniqueResultException
     */
    public function abilityShow(
        Request $request,
        AbilityVersionGroupRepository $abilityVersionGroupRepository
    ): Response {
        return $this->render('Ability/show.html.twig', [
            'ability' => $this->abilityRepository->findOneBySlugWithRelation($request->get('slug_ability')),
            'abilityVersionGroup' => $abilityVersionGroupRepository->findAbilityVersionGroupBySlug($request->get('slug_ability')),
        ]);
    }

    /**
     * Display all abilities
     *
     * @Route (path="/talents", name="ability_index")
     *
     * @param Request $request
     * @param FilterBuilderUpdaterInterface $builderUpdater
     * @param PaginatorInterface $paginator
     * @return Response
     */
    public function abilityIndex(
        Request $request,
        FilterBuilderUpdaterInterface $builderUpdater,
        PaginatorInterface $paginator
    ): Response {

        $abilitiesQuery = $this->abilityRepository->queryAll($this->languageRepository->findOneBy(['code' => 'fr']));

        $filterForm = $this->createForm(AbilityFormFilterType::class, null, [
            'method' => 'GET',
        ]);
        if ($request->query->has($filterForm->getName())) {
            $filterForm->submit($request->query->get($filterForm->getName()));
            $builderUpdater->addFilterConditions($filterForm, $abilitiesQuery);
        }

        $abilities = $paginator->paginate(
            $abilitiesQuery,
            $request->query->getInt('page', 1),
            20,
            [
                'wrap-queries' => true,
            ]
        );

        return $this->render('Ability/index.html.twig', [
            'abilities' => $abilities,
            'filters' => $filterForm->createView(),
        ]);
    }

}

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
 * @property AbilityVersionGroupRepository $abilityVersionGroupRepository
 */
class AbilityController extends AbstractController
{

    /**
     * AbilityController constructor.
     * @param AbilityRepository $abilityRepository
     * @param LanguageRepository $languageRepository
     * @param AbilityVersionGroupRepository $abilityVersionGroupRepository
     */
    public function __construct(
        AbilityRepository $abilityRepository,
        LanguageRepository $languageRepository,
        AbilityVersionGroupRepository $abilityVersionGroupRepository
    ) {
        $this->abilityRepository = $abilityRepository;
        $this->languageRepository = $languageRepository;
        $this->abilityVersionGroupRepository = $abilityVersionGroupRepository;
    }

    /**
     * Display the detailed ability and related pokemons with this ability
     *
     * @Route (path="{code}/talent/{slug}", name="ability_show")
     *
     * @param string $code
     * @param string $slug
     * @return Response
     * @throws NonUniqueResultException
     */
    public function abilityShow(string $code, string $slug): Response {
        $ability = $this->abilityRepository->findOneBySlugWithRelation($slug, $code);
        return $this->render('Ability/show.html.twig', [
            'ability' => $ability,
            'abilityVersionGroup' => $this->abilityVersionGroupRepository->findAbilityVersionGroupByAbility($ability),
        ]);
    }

    /**
     * Display all abilities
     *
     * @Route (path="{code}/talents", name="ability_index")
     *
     * @param Request $request
     * @param string $code
     * @param FilterBuilderUpdaterInterface $builderUpdater
     * @param PaginatorInterface $paginator
     * @return Response
     */
    public function abilityIndex(
        Request $request,
        string $code,
        FilterBuilderUpdaterInterface $builderUpdater,
        PaginatorInterface $paginator
    ): Response {

        $abilitiesQuery = $this->abilityRepository->queryAll($this->languageRepository->findOneBy(['code' => $code]));

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
            'codeLanguage' => $code,
            'filters' => $filterForm->createView(),
        ]);
    }

}

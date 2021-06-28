<?php


namespace App\Controller\Versions;

use App\Form\SearchPokemonType;
use App\Repository\Pokemon\PokemonRepository;
use App\Repository\Versions\GenerationRepository;
use App\Service\Users\LanguageService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GenerationController extends AbstractController
{

    /**
     * @var LanguageService $languageManager
     */
    private LanguageService $languageManager;

    /**
     * @var GenerationRepository $generationRepository
     */
    private GenerationRepository $generationRepository;

    /**
     * @var PokemonRepository $pokemonRepository
     */
    private PokemonRepository $pokemonRepository;

    /**
     * PokemonController constructor.
     *
     * @param LanguageService $languageManager
     * @param GenerationRepository $generationRepository
     * @param PokemonRepository $pokemonRepository
     */
    public function __construct
    (
        LanguageService $languageManager,
        GenerationRepository $generationRepository,
        PokemonRepository $pokemonRepository
    ) {
        $this->languageManager = $languageManager;
        $this->generationRepository = $generationRepository;
        $this->pokemonRepository = $pokemonRepository;
    }

    /**
     * @Route(path="/", name="generation_index")
     *
     * @param Request $request
     * @return Response
     */
    public function generationIndex(Request $request): Response {
        $form = $this->createForm(SearchPokemonType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $selectedPokemon = $form->getData();
            $selectedPokemon = $this->pokemonRepository->findOneBy(['name' => $selectedPokemon['name_pokemon']]);
            return $this->redirectToRoute('profile_pokemon', [
                'slug_pokemon' => $selectedPokemon->getSlug()
            ]);
        }

        return $this->render('Versions/generation_index.html.twig', [
            'generationList' => $this->generationRepository->getGenerationByLanguage(
                $this->languageManager->getLanguageByCode('fr')
            ),
            'formSearchPokemon' => $form->createView(),
        ]);
    }

}

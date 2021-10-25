<?php


namespace App\Controller\Front\Versions;

use App\Form\SearchPokemonType;
use App\Repository\Pokemon\PokemonRepository;
use App\Repository\Versions\GenerationRepository;
use App\Service\Users\LanguageService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class GenerationController
 * @package App\Controller\Versions
 *
 * @property LanguageService $languageManager
 * @property GenerationRepository $generationRepository
 * @property PokemonRepository $pokemonRepository
 */
class GenerationController extends AbstractController
{

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
     * @Route(path="/{code}/pokedex", name="generation_index")
     *
     * @param Request $request
     * @param string $code
     * @return Response
     */
    public function generationIndex(Request $request, string $code): Response {
        $language = $this->languageManager->getLanguageByCode($code);
        $form = $this->createForm(SearchPokemonType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $selectedPokemon = $this->pokemonRepository->findOneBy(['name' => $form->getData()['name_pokemon']]);
            return $this->redirectToRoute('profile_pokemon', [
                'code' => $code,
                'slug' => $selectedPokemon->getSlug(),
            ]);
        }

        return $this->render('Versions/generation_index.html.twig', [
            'formSearchPokemon' => $form->createView(),
            'generationList' => $this->generationRepository->getGenerationByLanguage($language),
            'language' => $language,
        ]);
    }

}

<?php


namespace App\Controller\Pokedex;


use App\Entity\Versions\Generation;
use App\Repository\Pokedex\PokedexRepository;
use App\Repository\Pokemon\PokemonRepository;
use App\Repository\Versions\GenerationRepository;
use App\Service\Users\LanguageService;
use Doctrine\ORM\NonUniqueResultException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class PokedexController
 * @package App\Controller\Pokedex
 *
 * @property LanguageService $languageService
 * @property PokemonRepository $pokemonRepository
 * @property PokedexRepository $pokedexRepository
 * @property GenerationRepository $generationRepository
 */
class PokedexController extends AbstractController
{

    /**
     * PokemonController constructor.
     *
     * @param PokedexRepository $pokedexRepository
     * @param LanguageService $languageManager
     * @param PokemonRepository $pokemonRepository
     * @param GenerationRepository $generationRepository
     */
    public function __construct(
        PokedexRepository $pokedexRepository,
        LanguageService $languageManager,
        PokemonRepository $pokemonRepository,
        GenerationRepository $generationRepository
    ) {
        $this->pokemonRepository = $pokemonRepository;
        $this->pokedexRepository = $pokedexRepository;
        $this->languageService = $languageManager;
        $this->generationRepository = $generationRepository;
    }

    /**
     * Display pokemon list
     *
     * @Route(path="/{code}/pokedex/{slug}", name="pokedex_list_generation")
     *
     * @param string $code
     * @param string $slug
     * @return Response
     * @throws NonUniqueResultException
     */
    function listing(string $code, string $slug): Response
    {
        $arrayPokedex = [];
        $generation = $this->generationRepository->findOneBySlugAndLanguage($slug, $code);
        $pokedexes = $this->pokedexRepository->getPokedexByGeneration($generation);
        foreach($pokedexes as $pokedex) {
            $arrayPokedex[] = [
                'pokedex' => $pokedex,
                'pokemons' => $this->pokemonRepository->getPokemonsByPokedex($pokedex),
            ];
        }
        return $this->render('Pokedex/listing.html.twig', [
            'arrayPokedex' => $arrayPokedex,
            'generation' => $generation,
        ]);
    }

}

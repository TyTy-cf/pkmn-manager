<?php


namespace App\Controller\Versions;

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
     * PokemonController constructor.
     *
     * @param LanguageService $languageManager
     * @param GenerationRepository $generationRepository
     */
    public function __construct
    (
        LanguageService $languageManager,
        GenerationRepository $generationRepository
    ) {
        $this->languageManager = $languageManager;
        $this->generationRepository = $generationRepository;
    }

    /**
     * @Route(path="/", name="generation_index")
     *
     * @param Request $request
     * @return Response
     */
    public function generationIndex(Request $request): Response {
        return $this->render('Versions/generation_index.html.twig', [
            'generationList' => $this->generationRepository->getGenerationByLanguage(
                $this->languageManager->getLanguageByCode('fr')
            ),
        ]);
    }

}

<?php


namespace App\Controller\Versions;

use App\Service\Users\LanguageManager;
use App\Service\Versions\GenerationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GenerationController extends AbstractController
{

    /**
     * @var GenerationService $generationManager
     */
    private GenerationService $generationManager;

    /**
     * @var LanguageManager $languageManager
     */
    private LanguageManager $languageManager;

    /**
     * PokemonController constructor.
     *
     * @param GenerationService $generationManager
     * @param LanguageManager $languageManager
     */
    public function __construct
    (
        GenerationService $generationManager,
        LanguageManager $languageManager
    )
    {
        $this->generationManager = $generationManager;
        $this->languageManager = $languageManager;
    }

    /**
     * @Route(path="/", name="generation_index")
     *
     * @param Request $request
     * @return Response
     */
    public function generationIndex(Request $request): Response {
        return $this->render('Versions/generation_index.html.twig', [
            'generationList' => $this->generationManager->getAllGenerationsByLanguage(
                $this->languageManager->getLanguageByCode('fr')
            ),
        ]);
    }

}

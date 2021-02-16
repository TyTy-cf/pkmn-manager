<?php


namespace App\Command\Moves;


use App\Command\AbstractCommand;
use App\Entity\Moves\MoveLearnMethod;
use App\Service\Api\ApiService;
use App\Service\Moves\MoveLearnMethodService;
use App\Service\Moves\PokemonMovesLearnVersionService;
use App\Service\Pokemon\PokemonService;
use App\Service\TextService;
use App\Service\Users\LanguageService;
use App\Service\Versions\VersionGroupService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PokemonMovesLearnVersionCommand extends AbstractCommand
{

    /**
     * @var VersionGroupService $versionGroupManager
     */
    private VersionGroupService $versionGroupManager;

    /**
     * @var TextService $textManager
     */
    private TextService $textManager;

    /**
     * @var MoveLearnMethodService $moveLearnMethodManager
     */
    private MoveLearnMethodService $moveLearnMethodManager;

    /**
     * @var PokemonService $pokemonManager
     */
    private PokemonService $pokemonManager;

    /**
     * ExcecCommand constructor
     * @param PokemonMovesLearnVersionService $pokemonMovesLearnVersionManager
     * @param VersionGroupService $versionGroupManager
     * @param ApiService $apiManager
     * @param TextService $textManager
     * @param LanguageService $languageManager
     * @param PokemonService $pokemonManager
     * @param MoveLearnMethodService $moveLearnMethodManager
     * @param EntityManagerInterface $em
     */
    public function __construct
    (
        PokemonMovesLearnVersionService $pokemonMovesLearnVersionManager,
        VersionGroupService $versionGroupManager,
        ApiService $apiManager,
        TextService $textManager,
        LanguageService $languageManager,
        PokemonService $pokemonManager,
        MoveLearnMethodService $moveLearnMethodManager,
        EntityManagerInterface $em
    )
    {
        $this->moveLearnMethodManager = $moveLearnMethodManager;
        $this->textManager = $textManager;
        $this->pokemonManager = $pokemonManager;
        $this->versionGroupManager = $versionGroupManager;
        parent::__construct($pokemonMovesLearnVersionManager, $languageManager, $apiManager, $em);
    }

    /**
     * ExcecCommand configuration
     */
    protected function configure()
    {
        $this
            ->setName('app:pokemon-move-learn:all')
            ->addArgument('offset', InputArgument::REQUIRED, 'Starting number to fetch pokemons')
            ->addArgument('limit', InputArgument::REQUIRED, 'Number of pokemon to load')
            ->addArgument('lang', InputArgument::REQUIRED, 'Language used')
            ->setDescription('Execute app:pokemon-move-learn:all to fetch all moves learned by pokemon language');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     * @throws NonUniqueResultException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $lang = $input->getArgument('lang');
        $offset = $input->getArgument('offset');
        $limit= $input->getArgument('limit');

        $output->writeln('');
        $output->writeln('<info>Fetching all moves-learn for pokemon by version...');
        $output->writeln('For language ' . $lang . '</info>');

        if ($this->checkLanguageExists($output, $lang))
        {
            $language = $this->languageManager->getLanguageByCode($lang);
            $versionGroupArray = $this->versionGroupManager->getArrayVersionGroup($language);
            $methodLearnMethodArray = $this->moveLearnMethodManager->getAllMoveLearnMethodByLanguage($language);
            $arrayIdMax = $this->manager->getLastPokemonIdInDataBase();
            $arrayPokemon = $this->pokemonManager->getPokemonOffsetLimitByLanguage(
                $language,
                $arrayIdMax[0][1] == null ? $offset : $arrayIdMax[0][1],
                $limit
            );
            // Reorder array on name
            $arrayGroupVersion = array();
            foreach ($versionGroupArray as $versionGroup)
            {
                $arrayGroupVersion[$versionGroup->getName()] = $versionGroup;
            }
            $arrayMoveLearnMethod = array();
            foreach ($methodLearnMethodArray as $moveLearnMethod)
            {
                $arrayMoveLearnMethod[$moveLearnMethod->getSlug()] = $moveLearnMethod;
            }

            //Initialize progress bar
            $progressBar = new ProgressBar($output, count($arrayPokemon));
            $progressBar->start();

            foreach ($arrayPokemon as $pokemon) {
                $this->manager->createMoveFromApiResponse(
                    $language,
                    $pokemon,
                    $arrayGroupVersion,
                    $arrayMoveLearnMethod
                );
                $progressBar->advance();
            }

            //End of the progressBar
            $progressBar->finish();

            return command::SUCCESS;
        }
        return command::FAILURE;
    }

}
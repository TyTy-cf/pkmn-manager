<?php


namespace App\Command\Moves;


use App\Command\AbstractCommand;
use App\Manager\Api\ApiManager;
use App\Manager\Moves\PokemonMovesLearnVersionManager;
use App\Manager\Users\LanguageManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class PokemonMovesLearnVersionCommand extends AbstractCommand
{

    /**
     * ExcecCommand constructor
     * @param PokemonMovesLearnVersionManager $pokemonMovesLearnVersionManager
     * @param ApiManager $apiManager
     * @param LanguageManager $languageManager
     */
    public function __construct
    (
        PokemonMovesLearnVersionManager $pokemonMovesLearnVersionManager,
        ApiManager $apiManager,
        LanguageManager $languageManager
    )
    {
        parent::__construct($pokemonMovesLearnVersionManager, $languageManager, $apiManager);
    }

    /**
     * ExcecCommand configuration
     */
    protected function configure()
    {
        $this
            ->setName('app:pokemon-move-learn:all')
            ->addArgument('gen', InputArgument::REQUIRED, 'Generation required')
            ->addArgument('lang', InputArgument::REQUIRED, 'Language used')
            ->setDescription('Execute app:pokemon-move-learn:all to fetch all moves learned by pokemon language');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     * @throws TransportExceptionInterface
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $lang = $input->getArgument('lang');

        $output->writeln('');
        $output->writeln('<info>Fetching all moves-learn for pokemon by version...');
        $output->writeln('For language ' . $lang . '</info>');

        $generation = $input->getArgument('gen');

        if ($this->checkLanguageExists($output, $lang))
        {
            $language = $this->languageManager->getLanguageByCode($lang);
            $arrayApiResponse = $this->apiManager->getAllPokemonJson()->toArray();

            //Initialize progress bar
            $progressBar = new ProgressBar($output, count($arrayApiResponse['results']));
            $progressBar->start();

            foreach ($arrayApiResponse['results'] as $apiResponse) {
                $this->manager->createMoveFromApiResponse($language, $apiResponse, $generation);
                $progressBar->advance();
            }

            //End of the progressBar
            $progressBar->finish();

            return command::SUCCESS;
        }
        return command::FAILURE;
    }

}
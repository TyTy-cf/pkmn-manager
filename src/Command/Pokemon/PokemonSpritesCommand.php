<?php


namespace App\Command\Pokemon;


use App\Command\AbstractCommand;
use App\Manager\Api\ApiManager;
use App\Manager\Pokemon\PokemonManager;
use App\Manager\Pokemon\PokemonSpritesVersionManager;
use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class PokemonSpritesCommand extends AbstractCommand
{

    /**
     * @var PokemonSpritesVersionManager $pokemonManagerVersionManager
     */
    private PokemonSpritesVersionManager $pokemonManagerVersionManager;

    /**
     * @var ApiManager $apiManager ;
     */
    private ApiManager $apiManager;

    /**
     * ExcecCommand constructor
     * @param PokemonSpritesVersionManager $pokemonManagerVersionManager
     * @param ApiManager $apiManager
     */
    public function __construct(PokemonSpritesVersionManager $pokemonManagerVersionManager,
                                ApiManager $apiManager)
    {
        $this->pokemonManagerVersionManager = $pokemonManagerVersionManager;
        $this->apiManager = $apiManager;
        parent::__construct();
    }

    /**
     * ExcecCommand configuration
     */
    protected function configure()
    {
        $this
            ->setName('app:sprites:all')
            ->addArgument('lang', InputArgument::REQUIRED, 'Language used')
            ->setDescription('Execute app:sprites to fetch sprites for all pokemons by version');
    }

    /**
     * Execute app:pokemon:all
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     * @throws TransportExceptionInterface
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        //Fetch parameter
        $lang = $input->getArgument('lang');
        if ($this->checkLanguageExists($output, $lang))
        {
            $output->writeln('Pokemon table and version-group are required');

            $output->writeln('');
            $output->writeln('<info>Fetching all pokemons for language ' . $lang . '</info>');

            //Get list of pokemons
            $arrayApiPokemons = $this->apiManager->getAllPokemonJson()->toArray();

            //Initialise progress bar
            $progressBar = new ProgressBar($output, count($arrayApiPokemons['results']));
            $progressBar->start();

            // Check row
            foreach ($arrayApiPokemons['results'] as $pokemon) {
                //Save pokemon in BDD
                $apiResponse = $this->apiManager->getDetailed($pokemon['url']);
                $this->pokemonManagerVersionManager->saveSpritesFromApi($lang, $apiResponse->toArray()['sprites']['versions'], $pokemon['name']);

                //Advance progressBar
                $progressBar->advance();
            }

            $progressBar->finish();

            return command::SUCCESS;
        }
        return command::FAILURE;
    }
}
<?php

namespace App\Command\Pokemon;

use App\Command\AbstractCommand;
use App\Manager\Api\ApiManager;
use App\Manager\Pokemon\PokemonManager;
use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

/**
 * Pokemon manager
 */
class PokemonCommand extends AbstractCommand
{

    /**
     * @var PokemonManager $pokemonManager
     */
    private PokemonManager $pokemonManager;

    /**
     * @var ApiManager $apiManager ;
     */
    private ApiManager $apiManager;

    /**
     * ExcecCommand constructor
     * @param PokemonManager $pokemonManager
     * @param ApiManager $apiManager
     */
    public function __construct(PokemonManager $pokemonManager,
                                ApiManager $apiManager)
    {
        $this->pokemonManager = $pokemonManager;
        $this->apiManager = $apiManager;
        parent::__construct();
    }

    /**
     * ExcecCommand configuration
     */
    protected function configure()
    {
        $this
            ->setName('app:pokemon:all')
            ->addArgument('lang', InputArgument::REQUIRED, 'Language used')
            ->setDescription('Execute app:pokemon to fetech all pokemon for language');
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
            $language = $this->languageManager->createLanguage($lang);

//            $this->executeCommand($output, $lang, 'app:ability:all');
//
//            $this->executeCommand($output, $lang, 'app:type:all');
//
//            $this->executeCommand($output, $lang, 'app:damage-relation:all');
//
//            $this->executeCommand($output, $lang, 'app:damage-class:all');
//
//            $this->executeCommand($output, $lang, 'app:nature:all');
//
//            $this->executeCommand($output, $lang, 'app:version:all');

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
                $apiResponse = $this->apiManager->getPokemonFromName($pokemon['name']);
                $this->pokemonManager->saveNewPokemon($lang, $apiResponse->toArray());
    
                //Advance progressBar
                $progressBar->advance();
            }
    
            $progressBar->finish();
    
            return command::SUCCESS;
        }
        return command::FAILURE;
    }

}
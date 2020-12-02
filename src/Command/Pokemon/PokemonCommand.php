<?php

namespace App\Command\Pokemon;

use App\Command\AbstractCommand;
use App\Manager\Api\ApiManager;
use App\Manager\Pokemon\PokemonManager;
use App\Manager\Users\LanguageManager;
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
     * ExcecCommand constructor
     * @param PokemonManager $pokemonManager
     * @param LanguageManager $languageManager
     * @param ApiManager $apiManager
     */
    public function __construct
    (
        PokemonManager $pokemonManager,
        LanguageManager $languageManager,
        ApiManager $apiManager
    )
    {
        parent::__construct($pokemonManager, $languageManager, $apiManager);
    }

    /**
     * ExcecCommand configuration
     */
    protected function configure()
    {
        $this
            ->setName('app:pokemon:all')
            ->addArgument('lang', InputArgument::REQUIRED, 'Language used')
            ->setDescription('Execute app:pokemon to fetch all pokemon for language');
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
        $output->writeln('');
        $output->writeln('<info>Fetching all pokemons...');

        return $this->executeFromManager($input, $output, $this->apiManager->getAllPokemonJson()->toArray());
    }

}
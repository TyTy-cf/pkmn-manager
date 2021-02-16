<?php


namespace App\Command\Pokemon;


use App\Command\AbstractCommand;
use App\Service\Api\ApiService;
use App\Service\Pokemon\PokemonFormService;
use App\Service\Pokemon\PokemonService;
use App\Service\Users\LanguageService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PokemonFormCommand extends AbstractCommand
{
    /**
     * @var PokemonService $pokemonManager
     */
    private PokemonService $pokemonManager;

    /**
     * ExcecCommand constructor
     *
     * @param PokemonFormService $pokemonFormManager
     * @param PokemonService $pokemonManager
     * @param LanguageService $languageManager
     * @param ApiService $apiManager
     * @param EntityManagerInterface $em
     */
    public function __construct
    (
        PokemonFormService $pokemonFormManager,
        PokemonService $pokemonManager,
        LanguageService $languageManager,
        ApiService $apiManager,
        EntityManagerInterface $em
    )
    {
        $this->pokemonManager = $pokemonManager;
        parent::__construct($pokemonFormManager, $languageManager, $apiManager, $em);
    }

    /**
     * ExcecCommand configuration
     */
    protected function configure()
    {
        $this->setName('app:pokemon-form:all')
            ->addArgument('lang', InputArgument::REQUIRED, 'Language used')
            ->setDescription('Execute app:pokemon to fetch all pokemon for language')
        ;
    }

    /**
     * Execute app:pokemon:all
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('');
        $output->writeln('<info>Fetching all pokemon-form...');

        $lang = $input->getArgument('lang');

        if ($this->checkLanguageExists($output, $lang))
        {
            $language = $this->languageManager->getLanguageByCode($lang);
            $arrayPokemons = $this->pokemonManager->getAllPokemonByLanguage($language);
            //Initialise progress bar
            $progressBar = new ProgressBar($output, sizeof($arrayPokemons));
            $progressBar->start();
            foreach ($arrayPokemons as $pokemon) {
                $this->manager->createPokemonFormFromPokemon($pokemon, $language);
                $progressBar->advance();
            }

            $progressBar->finish();

            return command::SUCCESS;
        }
        return command::FAILURE;

    }

}

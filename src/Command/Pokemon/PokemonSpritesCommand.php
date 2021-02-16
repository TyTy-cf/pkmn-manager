<?php


namespace App\Command\Pokemon;


use App\Command\AbstractCommand;
use App\Service\Api\ApiService;
use App\Service\Pokemon\PokemonSpritesVersionService;
use App\Service\Users\LanguageService;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class PokemonSpritesCommand extends AbstractCommand
{

    /**
     * ExcecCommand constructor
     * @param PokemonSpritesVersionService $pokemonManagerVersionManager
     * @param LanguageService $languageManager
     * @param ApiService $apiManager
     * @param EntityManagerInterface $em
     */
    public function __construct
    (
        PokemonSpritesVersionService $pokemonManagerVersionManager,
        LanguageService $languageManager,
        ApiService $apiManager,
        EntityManagerInterface $em
    )
    {
        parent::__construct($pokemonManagerVersionManager, $languageManager, $apiManager, $em);
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
        $output->writeln('');
        $output->writeln('<info>Fetching all sprites for pokemon...');

        return $this->executeFromManager($input, $output, $this->apiManager->getAllPokemonJson()->toArray());
    }
}
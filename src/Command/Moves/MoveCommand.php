<?php


namespace App\Command\Moves;


use App\Command\AbstractCommand;
use App\Manager\Api\ApiManager;
use App\Manager\Moves\MoveManager;
use App\Manager\Users\LanguageManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class MoveCommand extends AbstractCommand
{

    /**
     * @var MoveManager $moveManager
     */
    private MoveManager $moveManager;

    /**
     * @var ApiManager $apiManager ;
     */
    private ApiManager $apiManager;

    /**
     * @var LanguageManager $languageManager
     */
    private LanguageManager $languageManager;

    /**
     * ExcecCommand constructor
     * @param MoveManager $moveManager
     * @param ApiManager $apiManager
     * @param LanguageManager $languageManager
     */
    public function __construct(MoveManager $moveManager,
                                ApiManager $apiManager,
                                LanguageManager $languageManager)
    {
        $this->moveManager = $moveManager;
        $this->apiManager = $apiManager;
        $this->languageManager = $languageManager;
        parent::__construct();
    }

    /**
     * ExcecCommand configuration
     */
    protected function configure()
    {
        $this
            ->setName('app:move:all')
            ->addArgument('lang', InputArgument::REQUIRED, 'Language used')
            ->setDescription('Execute app:pokemon to fetch all moves for language');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     * @throws TransportExceptionInterface
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // Fetch parameter
        $lang = $input->getArgument('lang');

        if ($this->checkLanguageExists($output, $lang))
        {
            $language = $this->languageManager->createLanguage($lang);

            $output->writeln('');
            $output->writeln('<info>Fetching all moves for language ' . $lang . '</info>');

            //Get list of pokemons
            $arrayApiMoves = $this->apiManager->getAllMoveJson()->toArray();

            // Initialise progress bar
            $progressBar = new ProgressBar($output, count($arrayApiMoves['results']));
            $progressBar->start();

            foreach ($arrayApiMoves['results'] as $move)
            {
                $detailedMove = $this->apiManager->getDetailed($move['url']);
                $this->moveManager->saveMove($language, $detailedMove);
                // Advance progressBar
                $progressBar->advance();
            }
            return command::SUCCESS;
        }
        return command::FAILURE;
    }
}
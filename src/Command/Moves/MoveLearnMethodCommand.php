<?php


namespace App\Command\Moves;


use App\Command\AbstractCommand;
use App\Manager\Api\ApiManager;
use App\Manager\Moves\MoveLearnMethodManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class MoveLearnMethodCommand extends AbstractCommand
{

    /**
     * @var ApiManager $apiManager ;
     */
    private ApiManager $apiManager;

    /**
     * @var MoveLearnMethodManager $moveLearnMethodManager
     */
    private MoveLearnMethodManager $moveLearnMethodManager;

    /**
     * ExcecCommand constructor
     * @param ApiManager $apiManager
     * @param MoveLearnMethodManager $moveLearnMethodManager
     */
    public function __construct(ApiManager $apiManager,
                                MoveLearnMethodManager $moveLearnMethodManager)
    {
        $this->apiManager = $apiManager;
        $this->moveLearnMethodManager = $moveLearnMethodManager;
        parent::__construct();
    }

    /**
     * ExcecCommand configuration
     */
    protected function configure()
    {
        $this
            ->setName('app:move-learn-method:all')
            ->addArgument('lang', InputArgument::REQUIRED, 'Language used')
            ->setDescription('Execute app:move-learn-method:all to fetch all MoveLearnMethod for required language');
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

        $output->writeln('');
        $output->writeln('<info>Fetching all move-learn-method for language ' . $lang . '</info>');

        //Get list of types
        $learnMethodList = $this->apiManager->getAllMoveLearnMethodJson()->toArray();

        //Initialize progress bar
        $progressBar = new ProgressBar($output, count($learnMethodList['results']));
        $progressBar->start();

        foreach ($learnMethodList['results'] as $learnMethod) {
            $this->moveLearnMethodManager->createMoveLearnMethodIfNotExist($lang, $learnMethod);
            $progressBar->advance();
        }

        //End of the progressBar
        $progressBar->finish();

        return command::SUCCESS;
    }

}
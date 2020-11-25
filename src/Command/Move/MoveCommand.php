<?php


namespace App\Command\Move;


use App\Manager\Api\ApiManager;
use App\Manager\Moves\MoveManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MoveCommand extends Command
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
     * ExcecCommand constructor
     * @param MoveManager $moveManager
     * @param ApiManager $apiManager
     */
    public function __construct(MoveManager $moveManager, ApiManager $apiManager)
    {
        $this->moveManager = $moveManager;
        $this->apiManager = $apiManager;
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
            ->setDescription('Execute app:pokemon to fetech all pokemon for language');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {

    }
}
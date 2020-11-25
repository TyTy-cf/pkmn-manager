<?php


namespace App\Command\Infos;


use App\Manager\Api\ApiManager;
use App\Manager\Infos\AbilitiesManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class AbilitiesCommand extends Command
{

    /**
     * @var AbilitiesManager $abilitiesManager
     */
    private AbilitiesManager $abilitiesManager;

    /**
     * @var ApiManager $apiManager ;
     */
    private ApiManager $apiManager;

    /**
     * ExcecCommand constructor
     * @param AbilitiesManager $abilitiesManager
     * @param ApiManager $apiManager
     */
    public function __construct(AbilitiesManager $abilitiesManager, ApiManager $apiManager)
    {
        $this->abilitiesManager = $abilitiesManager;
        $this->apiManager = $apiManager;
        parent::__construct();
    }

    /**
     * ExcecCommand configuration
     */
    protected function configure()
    {
        $this
            ->setName('app:abilities:all')
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
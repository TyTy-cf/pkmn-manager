<?php


namespace App\Command\Infos;


use App\Manager\Api\ApiManager;
use App\Manager\Infos\NatureManager;
use Symfony\Bridge\PhpUnit\TextUI\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class NatureCommand extends Command
{

    /**
     * @var NatureManager $natureManager
     */
    private NatureManager $moveManager;

    /**
     * @var ApiManager $apiManager ;
     */
    private ApiManager $apiManager;

    /**
     * ExcecCommand constructor
     * @param NatureManager $natureManager
     * @param ApiManager $apiManager
     */
    public function __construct(NatureManager $natureManager, ApiManager $apiManager)
    {
        $this->natureManager = $natureManager;
        $this->apiManager = $apiManager;
        parent::__construct();
    }

    /**
     * ExcecCommand configuration
     */
    protected function configure()
    {
        $this
            ->setName('app:nature:all')
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
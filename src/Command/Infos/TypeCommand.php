<?php


namespace App\Command\Infos;


use App\Manager\Api\ApiManager;
use App\Manager\Infos\Type\TypeManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class TypeCommand extends Command
{
    /**
     * @var TypeManager $typeManager
     */
    private TypeManager $typeManager;

    /**
     * @var ApiManager $apiManager ;
     */
    private ApiManager $apiManager;

    /**
     * ExcecCommand constructor
     * @param TypeManager $typeManager
     * @param ApiManager $apiManager
     */
    public function __construct(TypeManager $typeManager, ApiManager $apiManager)
    {
        $this->typeManager = $typeManager;
        $this->apiManager = $apiManager;
        parent::__construct();
    }

    /**
     * ExcecCommand configuration
     */
    protected function configure()
    {
        $this
            ->setName('app:type:all')
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
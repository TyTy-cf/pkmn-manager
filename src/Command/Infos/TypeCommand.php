<?php


namespace App\Command\Infos;


use App\Manager\Api\ApiManager;
use App\Manager\Infos\Type\TypeDamageFromTypeManager;
use App\Manager\Infos\Type\TypeManager;
use App\Manager\Users\LanguageManager;
use App\Repository\Infos\Type\TypeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
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
     * @var ApiManager $apiManager
     */
    private ApiManager $apiManager;


    /**
     * ExcecCommand constructor
     * @param TypeManager $typeManager
     * @param ApiManager $apiManager
     */
    public function __construct(
        TypeManager $typeManager,
        ApiManager $apiManager
    ) {
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
     * Execute the command app:type:all
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        //Fetch parameter
        $lang = $input->getArgument('lang');

        //Get list of types
        $typesList = $this->apiManager->getDetailed("https://pokeapi.co/api/v2/type")->toarray();

        //Initialise progress bar
        $progressBar = new ProgressBar($output, count($typesList['results']));

        // If not exist, save type in Database according in language
        $this->typeManager->createIfNotExist($lang, $typesList, $progressBar);

        //End of the progressBar
        $progressBar->finish();

        return command::SUCCESS;
    }
}
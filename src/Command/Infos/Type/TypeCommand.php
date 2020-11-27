<?php


namespace App\Command\Infos\Type;


use App\Manager\Api\ApiManager;
use App\Manager\Infos\Type\TypeManager;
use App\Manager\Users\LanguageManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

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
     * @var LanguageManager $languageManager
     */
    private LanguageManager $languageManager;

    /**
     * ExcecCommand constructor
     * @param TypeManager $typeManager
     * @param ApiManager $apiManager
     * @param LanguageManager $languageManager
     */
    public function __construct(
        TypeManager $typeManager,
        ApiManager $apiManager,
        LanguageManager $languageManager
    ) {
        $this->typeManager = $typeManager;
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
            ->setName('app:type:all')
            ->addArgument('lang', InputArgument::REQUIRED, 'Language used')
            ->setDescription('Execute app:pokemon to fetech all pokemon for language');
    }

    /**
     * Execute the command app:type:all
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     * @throws TransportExceptionInterface
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        //Fetch parameter
        $lang = $input->getArgument('lang');

        //Get list of types
        $typesList = $this->apiManager->getAllTypeJson()->toArray();

        //Initialize progress bar
        $progressBar = new ProgressBar($output, count($typesList['results']));
        $progressBar->start();

        // Fetch the right language
        $language = $this->languageManager->getLanguageByCode($lang);

        foreach ($typesList['results'] as $type) {
            $this->typeManager->createTypeIfNotExist($language, $type);
            $progressBar->advance();
        }

        //End of the progressBar
        $progressBar->finish();

        return command::SUCCESS;
    }
}
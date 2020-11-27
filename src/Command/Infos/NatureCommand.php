<?php


namespace App\Command\Infos;


use App\Manager\Api\ApiManager;
use App\Manager\Infos\NatureManager;
use App\Manager\Users\LanguageManager;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class NatureCommand extends Command
{

    /**
     * @var NatureManager $natureManager
     */
    private NatureManager $natureManager;

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
     * @param NatureManager $natureManager
     * @param LanguageManager $languageManager
     * @param ApiManager $apiManager
     */
    public function __construct(NatureManager $natureManager,
                                LanguageManager $languageManager,
                                ApiManager $apiManager)
    {
        $this->natureManager = $natureManager;
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
            ->setName('app:nature:all')
            ->addArgument('lang', InputArgument::REQUIRED, 'Language used')
            ->setDescription('Execute app:pokemon to fetech all pokemon for language');
    }

    /**
     * Execute the command to fetch all natures
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int
     * @throws TransportExceptionInterface
     * @throws NonUniqueResultException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        //Fetch parameter
        $lang = $input->getArgument('lang');

        //Get list of types
        $natureList = $this->apiManager->getAllNatureJson()->toArray();

        //Initialize progress bar
        $progressBar = new ProgressBar($output, count($natureList['results']));
        $progressBar->start();

        // Fetch the right language
        $language = $this->languageManager->getLanguageByCode($lang);

        foreach ($natureList['results'] as $nature) {
            $detailedNatureArray = $this->apiManager->getDetailed($nature['url'])->toArray();
            $this->natureManager->createNatureIfNotExist($language, $detailedNatureArray);
            $progressBar->advance();
        }

        //End of the progressBar
        $progressBar->finish();

        return Command::SUCCESS;

    }
}
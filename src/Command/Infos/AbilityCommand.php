<?php


namespace App\Command\Infos;


use App\Manager\Api\ApiManager;
use App\Manager\Infos\AbilitiyManager;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class AbilityCommand extends Command
{

    /**
     * @var AbilitiyManager $abilitiesManager
     */
    private AbilitiyManager $abilitiesManager;

    /**
     * @var ApiManager $apiManager ;
     */
    private ApiManager $apiManager;

    /**
     * ExcecCommand constructor
     * @param AbilitiyManager $abilitiesManager
     * @param ApiManager $apiManager
     */
    public function __construct(AbilitiyManager $abilitiesManager,
                                ApiManager $apiManager)
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
            ->setName('app:ability:all')
            ->addArgument('lang', InputArgument::REQUIRED, 'Language used')
            ->setDescription('Execute app:pokemon to fetech all pokemon for language');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int
     * @throws TransportExceptionInterface|NonUniqueResultException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // Fetch parameter
        $lang = $input->getArgument('lang');

        $output->writeln('');
        $output->writeln('<info>Fetching all abilities for language ' . $lang . '</info>');

        //Get list of types
        $abilitiesList = $this->apiManager->getAllAbilitiesJson()->toArray();

        //Initialize progress bar
        $progressBar = new ProgressBar($output, count($abilitiesList['results']));
        $progressBar->start();

        foreach ($abilitiesList['results'] as $ability) {
            $this->abilitiesManager->createAbilityIfNotExist($lang, $ability);
            $progressBar->advance();
        }

        //End of the progressBar
        $progressBar->finish();

        return command::SUCCESS;
    }
}
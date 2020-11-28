<?php


namespace App\Command\Move;


use App\Manager\Api\ApiManager;
use App\Manager\Moves\DamageClassManager;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class DamageClassCommand extends Command
{

    /**
     * @var DamageClassManager $damageClassManager
     */
    private DamageClassManager $damageClassManager;

    /**
     * @var ApiManager $apiManager ;
     */
    private ApiManager $apiManager;

    /**
     * ExcecCommand constructor
     * @param DamageClassManager $damageClassManager
     * @param ApiManager $apiManager
     */
    public function __construct(DamageClassManager $damageClassManager,
                                ApiManager $apiManager)
    {
        $this->damageClassManager = $damageClassManager;
        $this->apiManager = $apiManager;
        parent::__construct();
    }

    /**
     * ExcecCommand configuration
     */
    protected function configure()
    {
        $this
            ->setName('app:damage-class:all')
            ->addArgument('lang', InputArgument::REQUIRED, 'Language used')
            ->setDescription('Execute app:pokemon to fetch all pokemon for language');
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
        $output->writeln('<info>Fetching all damages-class for language ' . $lang . '</info>');

        // Fetch parameter
        $lang = $input->getArgument('lang');

        //Get list of types
        $damageClassList = $this->apiManager->getAllDamageClassJson()->toArray();

        //Initialize progress bar
        $progressBar = new ProgressBar($output, count($damageClassList['results']));
        $progressBar->start();

        foreach ($damageClassList['results'] as $damageClass) {
            $this->damageClassManager->createDamageClassIfNotExist($lang, $damageClass);
            $progressBar->advance();
        }

        //End of the progressBar
        $progressBar->finish();

        return command::SUCCESS;
    }

}
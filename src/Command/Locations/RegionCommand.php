<?php


namespace App\Command\Locations;


use App\Command\AbstractCommand;
use App\Manager\Api\ApiManager;
use App\Manager\Locations\RegionManager;
use App\Manager\Pokedex\EvolutionTriggerManager;
use App\Manager\Users\LanguageManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class RegionCommand extends AbstractCommand
{

    /**
     * ExcecCommand constructor
     * @param RegionManager $regionManagerManager
     * @param LanguageManager $languageManager
     * @param ApiManager $apiManager
     * @param EntityManagerInterface $em
     */
    public function __construct(
        RegionManager $regionManagerManager,
        LanguageManager $languageManager,
        ApiManager $apiManager,
        EntityManagerInterface $em
    )
    {
        parent::__construct($regionManagerManager, $languageManager, $apiManager, $em);
    }

    /**
     * ExcecCommand configuration
     */
    protected function configure()
    {
        $this
            ->setName('app:region:all')
            ->addArgument('lang', InputArgument::REQUIRED, 'Language used')
            ->setDescription('Execute app:region:all to fetch all regions and related locations for language');
    }

    /**
     * Execute the command to fetch all natures
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int
     * @throws TransportExceptionInterface
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('');
        $output->writeln('<info>Fetching all regions and related locations...');

        return $this->executeFromManager($input, $output, $this->apiManager->getRegionsJson()->toArray());
    }
}
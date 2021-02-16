<?php


namespace App\Command\Locations;


use App\Command\AbstractCommand;
use App\Service\Api\ApiService;
use App\Service\Locations\RegionService;
use App\Service\Pokedex\EvolutionTriggerService;
use App\Service\Users\LanguageManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class RegionCommand extends AbstractCommand
{

    /**
     * ExcecCommand constructor
     * @param RegionService $regionManagerManager
     * @param LanguageManager $languageManager
     * @param ApiService $apiManager
     * @param EntityManagerInterface $em
     */
    public function __construct(
        RegionService $regionManagerManager,
        LanguageManager $languageManager,
        ApiService $apiManager,
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

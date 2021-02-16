<?php


namespace App\Command\Pokedex;


use App\Command\AbstractCommand;
use App\Service\Api\ApiService;
use App\Service\Pokedex\EggGroupService;
use App\Service\Users\LanguageService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class EggGroupCommand extends AbstractCommand
{

    /**
     * ExcecCommand constructor
     * @param EggGroupService $eggGroupManager
     * @param LanguageService $languageManager
     * @param ApiService $apiManager
     * @param EntityManagerInterface $em
     */
    public function __construct(
        EggGroupService $eggGroupManager,
        LanguageService $languageManager,
        ApiService $apiManager,
        EntityManagerInterface $em
    )
    {
        parent::__construct($eggGroupManager, $languageManager, $apiManager, $em);
    }

    /**
     * ExcecCommand configuration
     */
    protected function configure()
    {
        $this
            ->setName('app:egg-group:all')
            ->addArgument('lang', InputArgument::REQUIRED, 'Language used')
            ->setDescription('Execute app:nature:all to fetch all natures for language');
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
        $output->writeln('<info>Fetching all egg group...');

        return $this->executeFromManager($input, $output, $this->apiManager->getEggGroupJson()->toArray());
    }
}
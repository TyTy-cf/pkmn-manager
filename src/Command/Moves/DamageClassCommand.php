<?php


namespace App\Command\Moves;


use App\Command\AbstractCommand;
use App\Manager\Api\ApiManager;
use App\Manager\Moves\DamageClassManager;
use App\Manager\Users\LanguageManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class DamageClassCommand extends AbstractCommand
{

    /**
     * ExcecCommand constructor
     * @param DamageClassManager $damageClassManager
     * @param LanguageManager $languageManager
     * @param ApiManager $apiManager
     * @param EntityManagerInterface $em
     */
    public function __construct(
        DamageClassManager $damageClassManager,
        LanguageManager $languageManager,
        ApiManager $apiManager,
        EntityManagerInterface $em
    )
    {
        parent::__construct($damageClassManager, $languageManager, $apiManager, $em);
    }

    /**
     * ExcecCommand configuration
     */
    protected function configure()
    {
        $this
            ->setName('app:damage-class:all')
            ->addArgument('lang', InputArgument::REQUIRED, 'Language used')
            ->setDescription('Execute app:damage-class:all to fetch all damage class for language');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     * @throws TransportExceptionInterface
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('');
        $output->writeln('<info>Fetching all damages-class...');

        return $this->executeFromManager($input, $output, $this->apiManager->getAllDamageClassJson()->toArray());
    }

}
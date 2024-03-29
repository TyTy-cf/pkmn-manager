<?php


namespace App\Command;


use App\Service\AbstractService;
use App\Service\Api\ApiService;
use App\Service\Users\LanguageService;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class AbstractCommand
 * @package App\Command
 *
 * @property AbstractService $manager
 * @property LanguageService $languageManager
 * @property ApiService $apiManager
 * @property EntityManagerInterface $em
 */
abstract class AbstractCommand extends Command
{

    protected array $supportedLanguages = ['fr', 'en'];

    /**
     * AbstractCommand constructor.
     * @param AbstractService $manager
     * @param LanguageService $languageManager
     * @param ApiService $apiManager
     * @param EntityManagerInterface $em
     */
    public function __construct (
        AbstractService $manager,
        LanguageService $languageManager,
        ApiService $apiManager,
        EntityManagerInterface $em
    ) {
        $this->em = $em;
        $this->languageManager = $languageManager;
        $this->manager = $manager;
        $this->apiManager = $apiManager;
        parent::__construct();
    }

    /**
     * Allow to execute an other Command
     * @param OutputInterface $output
     * @param string $lang
     * @param string $command
     * @return int
     * @throws Exception
     */
    protected function executeCommand(OutputInterface $output, string $lang, string $command): int {
        $command = $this->getApplication()->find($command);
        $langArguments = ['lang' => $lang];
        // Run Ability Command
        $cmdInput = new ArrayInput($langArguments);
        return $command->run($cmdInput, $output);
    }

    /**
     * @param OutputInterface $output
     * @param string $lang
     * @return bool
     */
    protected function checkLanguageExists(OutputInterface $output, string $lang) {
        if (!in_array($lang, $this->supportedLanguages)) {
            $output->writeln('<fg=red>' . $lang . ' isn\'t a supported language</>');
            return false;
        }
        return true;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @param array $arrayApiResponse
     * @return int
     */
    protected function executeFromManager (
        InputInterface $input,
        OutputInterface $output,
        array $arrayApiResponse
    ) {
        // Fetch parameter
        $lang = $input->getArgument('lang');

        $output->writeln('For language ' . $lang . '</info>');

        if ($this->checkLanguageExists($output, $lang)) {
           $language = $this->languageManager->getLanguageByCode($lang);

            //Initialize progress bar
            $progressBar = new ProgressBar($output, count($arrayApiResponse['results']));
            $progressBar->start();

            foreach ($arrayApiResponse['results'] as $apiResponse) {
                $this->manager->createFromApiResponse($language, $apiResponse);
                $progressBar->advance();
            }
            $this->em->flush();

            //End of the progressBar
            $progressBar->finish();

            return command::SUCCESS;
        }
        return command::FAILURE;
    }
}

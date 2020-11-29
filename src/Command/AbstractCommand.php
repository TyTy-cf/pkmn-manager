<?php


namespace App\Command;


use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\OutputInterface;

abstract class AbstractCommand extends Command
{

    protected array $supportedLanguages = ['fr', 'en'];

    /**
     * Allow to execute an other Command
     * @param OutputInterface $output
     * @param string $lang
     * @param string $command
     * @return int
     * @throws Exception
     */
    protected function executeCommand(OutputInterface $output, string $lang, string $command): int
    {
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
    protected function checkLanguageExists(OutputInterface $output, string $lang)
    {
        if (!in_array($lang, $this->supportedLanguages))
        {
            $output->writeln('<fg=red>' . $lang . ' isn\'t a supported language</>');
            return false;
        }
        return true;
    }
}
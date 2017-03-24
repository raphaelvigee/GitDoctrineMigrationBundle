<?php

namespace RaphaelVigee\GitDoctrineMigrationBundle\Service;

use RaphaelVigee\GitDoctrineMigrationBundle\Model\CommandResult;

class CommandService
{
    /**
     * @var string
     */
    protected $kernelRootDir;


    public function __construct($kernelRootDir)
    {
        $this->kernelRootDir = $kernelRootDir;
    }

    public function run($command, $shouldReturnCode = false)
    {
        $projectRootDir = realpath($this->kernelRootDir.'/../');

        exec(sprintf('cd "%s" && %s 2>&1', $projectRootDir, $command), $outputAndErrors, $returnCode);

        if ($shouldReturnCode) {
            return $returnCode;
        }

        return new CommandResult($returnCode, $outputAndErrors);
    }

    public function runGetFirstLine($command)
    {
        return $this->getFirstLine($this->run($command));
    }

    protected function getFirstLine(CommandResult $commandResult)
    {
        $output = $commandResult->getOutput();

        return @$output[0];
    }
}

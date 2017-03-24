<?php

namespace RaphaelVigee\GitDoctrineMigrationBundle\Service;

use RaphaelVigee\GitDoctrineMigrationBundle\Model\CommandResult;

/**
 * Class CommandService
 *
 * @package RaphaelVigee\GitDoctrineMigrationBundle\Service
 */
class CommandService
{
    /**
     * @var string
     */
    protected $kernelRootDir;


    /**
     * CommandService constructor.
     *
     * @param $kernelRootDir
     */
    public function __construct($kernelRootDir)
    {
        $this->kernelRootDir = $kernelRootDir;
    }

    /**
     * @param      $command
     * @param bool $shouldReturnCode
     *
     * @return CommandResult
     */
    public function run($command, $shouldReturnCode = false)
    {
        $projectRootDir = realpath($this->kernelRootDir.'/../');

        exec(sprintf('cd "%s" && %s 2>&1', $projectRootDir, $command), $outputAndErrors, $returnCode);

        if ($shouldReturnCode) {
            return $returnCode;
        }

        return new CommandResult($returnCode, $outputAndErrors);
    }

    /**
     * @param $command
     *
     * @return mixed
     */
    public function runGetFirstLine($command)
    {
        return $this->getFirstLine($this->run($command));
    }

    /**
     * @param CommandResult $commandResult
     *
     * @return mixed
     */
    protected function getFirstLine(CommandResult $commandResult)
    {
        $output = $commandResult->getOutput();

        return @$output[0];
    }
}

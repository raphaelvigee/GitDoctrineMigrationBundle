<?php

namespace RaphaelVigee\GitDoctrineMigrationBundle\Service;

/**
 * Class GitService
 *
 * @package RaphaelVigee\GitDoctrineMigrationBundle\Service
 */
class GitService extends CommandService
{
    /**
     * GitService constructor.
     *
     * @param $kernelRootDir
     */
    public function __construct($kernelRootDir)
    {
        parent::__construct($kernelRootDir);
    }

    /**
     * @param      $gitCommand
     * @param bool $shouldReturnCode
     *
     * @return \RaphaelVigee\GitDoctrineMigrationBundle\Model\CommandResult
     */
    public function gitRun($gitCommand, $shouldReturnCode = false)
    {
        $command = sprintf('git %s', $gitCommand);

        if(strpos($command, 'diff') !== false) {
            //die(dump($command));
        }

        return $this->run($command, $shouldReturnCode);
    }

    /**
     * @param $command
     *
     * @return mixed
     */
    public function gitRunGetFirstLine($command)
    {
        return $this->getFirstLine($this->gitRun($command));
    }

    /**
     * @return mixed
     */
    public function getCurrentHash()
    {
        return $this->gitRunGetFirstLine('rev-parse HEAD');
    }

    /**
     * @param $from
     * @param $to
     *
     * @return mixed
     */
    public function getDeletedMigrations($from, $to)
    {
        $command = sprintf(
            'diff --name-status %s..%s | grep "^D(\s?)app/DoctrineMigrations/Version\K(\d*)" -oP',
            $from,
            $to
        );

        $result = $this->gitRun($command);

        $migrations = $result->getOutput();

        rsort($migrations);

        return $migrations;
    }

    /**
     * @param $to
     *
     * @return mixed
     */
    public function getDeletedMigrationsTo($to)
    {
        $from = $this->getCurrentHash();

        return $this->getDeletedMigrations($from, $to);
    }
}

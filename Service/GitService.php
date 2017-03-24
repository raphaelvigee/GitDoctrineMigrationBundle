<?php

namespace RaphaelVigee\GitDoctrineMigrationBundle\Service;

class GitService extends CommandService
{
    public function __construct($kernelRootDir)
    {
        parent::__construct($kernelRootDir);
    }

    public function gitRun($gitCommand, $shouldReturnCode = false)
    {
        $command = sprintf('git %s', $gitCommand);

        if(strpos($command, 'diff') !== false) {
            //die(dump($command));
        }

        return $this->run($command, $shouldReturnCode);
    }

    public function gitRunGetFirstLine($command)
    {
        return $this->getFirstLine($this->gitRun($command));
    }

    public function getCurrentHash()
    {
        return $this->gitRunGetFirstLine('rev-parse HEAD');
    }

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

    public function getDeletedMigrationsTo($to)
    {
        $from = $this->getCurrentHash();

        return $this->getDeletedMigrations($from, $to);
    }
}

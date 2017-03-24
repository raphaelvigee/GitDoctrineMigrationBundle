<?php

namespace RaphaelVigee\GitDoctrineMigrationBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RestoreCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            // the name of the command (the part after "bin/console")
            ->setName('git:restore')
            // the short description shown while running "php bin/console list"
            ->setDescription('Restore DB dump')
            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('This command allows you to restore DB dump');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $container = $this->getContainer();
        $dumper = $container->get('raphael_vigee_git_doctrine_migration.mysql.dumper');
        $git = $container->get('raphael_vigee_git_doctrine_migration.git.service');

        $currentHash = $git->getCurrentHash();

        if (!$dumper->dumpExists($currentHash)) {
            throw new \Exception(sprintf("Dump file missing for hash %s", $currentHash));
        }

        $dumpPath = $dumper->getDumpPath($currentHash);

        $dumper->restore($dumpPath, true);
    }
}

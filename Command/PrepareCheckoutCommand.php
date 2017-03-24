<?php

namespace RaphaelVigee\GitDoctrineMigrationBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PrepareCheckoutCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            // the name of the command (the part after "bin/console")
            ->setName('git:prepare-checkout')
            // the short description shown while running "php bin/console list"
            ->setDescription('Prepare checkout branch')
            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('This command allows you to checkout an another branch')

            ->addArgument('target', InputArgument::REQUIRED, 'Target')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $container = $this->getContainer();
        $dumper = $container->get('raphael_vigee_git_doctrine_migration.mysql.dumper');
        $git = $container->get('raphael_vigee_git_doctrine_migration.git.service');

        $target = $input->getArgument('target');

        $versions = $git->getDeletedMigrationsTo($target);

        $commandName = 'doctrine:migrations:execute';
        $prototypeCommand = $this->getApplication()->get($commandName);
        $prototypeCommand->mergeApplicationDefinition();

        $dumper->dump();

        foreach ($versions as $v) {
            $migrationCommand = clone $prototypeCommand;

            $arguments = [
                'version'          => $v,
                '--down'           => true,
                '--no-interaction' => true,
            ];

            $migrationInput = new ArrayInput($arguments);
            $returnCode = $migrationCommand->run($migrationInput, $output);
        }

    }
}

<?php

namespace RaphaelVigee\GitDoctrineMigrationBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SwitchBranchCommand extends Command
{
    protected function configure()
    {
        $this
            // the name of the command (the part after "bin/console")
            ->setName('git:checkout')

            // the short description shown while running "php bin/console list"
            ->setDescription('Checkout branch')

            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('This command allows you to checkout an another branch');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // ...
    }
}

<?php

namespace RaphaelVigee\GitDoctrineMigrationBundle\Dumper;

use Doctrine\ORM\EntityManagerInterface;
use RaphaelVigee\GitDoctrineMigrationBundle\Service\GitService;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Class MySQLDumper
 *
 * @package RaphaelVigee\GitDoctrineMigrationBundle\Dumper
 */
class MySQLDumper
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var GitService
     */
    private $git;

    /**
     * @var string
     */
    private $kernelRootDir;

    /**
     * @var Filesystem
     */
    private $fs;


    public function __construct(EntityManagerInterface $em, GitService $git, $kernelRootDir)
    {
        $this->em = $em;
        $this->git = $git;
        $this->kernelRootDir = $kernelRootDir;
        $this->fs = new Filesystem();
    }

    /**
     * Dump
     */
    public function dump()
    {
        $connection = $this->em->getConnection();
        $user = $connection->getUsername();
        $password = $connection->getPassword();
        $host = $connection->getHost();
        $database = $connection->getDatabase();

        $currentHash = $this->git->getCurrentHash();

        $outFolder = sprintf('%s/../var/git-doctrine-migration', $this->kernelRootDir);

        if (!$this->fs->exists($outFolder)) {
            $this->fs->mkdir($outFolder, 0700);
        }

        $outPath = sprintf('%s/%s.sql', $outFolder, $currentHash);

        $command = sprintf(
            'mysqldump --user="%s" --password="%s" --host="%s" "%s" > "%s"',
            $user,
            $password,
            $host,
            $database,
            $outPath
        );

        exec($command);
    }

    public function restore($filePath, $force = true)
    {
        $connection = $this->em->getConnection();
        $user = $connection->getUsername();
        $password = $connection->getPassword();
        $host = $connection->getHost();
        $database = $connection->getDatabase();

        $command = sprintf(
            'mysql -u "%s" -p "%s" -h "%s" %s "%s" < "%s"',
            $user,
            $password,
            $host,
            $force ? '-f' : '',
            $database,
            $filePath
        );

        die(dump($command));

        exec($command);
    }
}

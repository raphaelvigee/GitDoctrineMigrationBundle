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


    /**
     * MySQLDumper constructor.
     *
     * @param EntityManagerInterface $em
     * @param GitService             $git
     * @param                        $kernelRootDir
     */
    public function __construct(EntityManagerInterface $em, GitService $git, $kernelRootDir)
    {
        $this->em = $em;
        $this->git = $git;
        $this->kernelRootDir = $kernelRootDir;
        $this->fs = new Filesystem();
    }

    /**
     * @param $hash
     *
     * @return string
     */
    public function getDumpPath($hash)
    {
        $outFolder = sprintf('%s/../var/git-doctrine-migration', $this->kernelRootDir);

        if (!$this->fs->exists($outFolder)) {
            $this->fs->mkdir($outFolder, 0700);
        }

        $outPath = sprintf('%s/%s.sql', $outFolder, $hash);

        return $outPath;
    }

    /**
     * @param $hash
     *
     * @return bool
     */
    public function dumpExists($hash)
    {
        return $this->fs->exists($this->getDumpPath($hash));
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

        $outPath = $this->getDumpPath($currentHash);

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

    /**
     * @param      $filePath
     * @param bool $force
     */
    public function restore($filePath, $force = true)
    {
        $connection = $this->em->getConnection();
        $user = $connection->getUsername();
        $password = $connection->getPassword();
        $host = $connection->getHost();
        $database = $connection->getDatabase();

        $command = sprintf(
            'mysql --user="%s" --password="%s" --host="%s" %s "%s" < "%s"',
            $user,
            $password,
            $host,
            $force ? '-f' : '',
            $database,
            $filePath
        );

        exec($command);
    }
}

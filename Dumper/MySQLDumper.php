<?php

namespace RaphaelVigee\GitDoctrineMigrationBundle\Dumper;

use Doctrine\ORM\EntityManagerInterface;

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
     * @var string
     */
    private $kernelRootDir;

    /**
     * MySQLDumper constructor.
     *
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em, $kernelRootDir)
    {
        $this->em = $em;
        $this->kernelRootDir = $kernelRootDir;
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

        $out = $this->kernelRootDir.'/../var/out.sql';

        exec(
            sprintf(
                'mysqldump --user=%s --password=%s --host=%s %s > %s',
                $user,
                $password,
                $host,
                $database,
                $out
            )
        );
    }
}

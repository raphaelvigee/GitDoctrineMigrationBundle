<?php

namespace RaphaelVigee\GitDoctrineMigrationBundle\Model;

class CommandResult
{
    private $returnCode;
    private $output;

    /**
     * CommandResult constructor.
     *
     * @param $returnCode
     * @param $output
     */
    public function __construct($returnCode, $output)
    {
        $this->returnCode = $returnCode;
        $this->output = $output;
    }

    /**
     * Get returnCode
     *
     * @return mixed
     */
    public function getReturnCode()
    {
        return $this->returnCode;
    }

    /**
     * Get output
     *
     * @return mixed
     */
    public function getOutput()
    {
        return $this->output;
    }
}

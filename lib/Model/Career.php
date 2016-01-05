<?php

/**
 * Description of Category
 *
 * 
 */

class Career
{

    protected $id;
    protected $name;
    protected $totalDuration;

    public function __construct($config)
    {
        $this->connection = MySQLiQuery::getObject($config['host'], $config['username'], $config['pass'], $config['DB']);
    }

    public function getCareers()
    {

        if (isset($this->connection)) {
            return $this->connection->select("ASSOCIATIVE", "career");
        }
        else {
            return "Database connection Error";
        }
    }

}

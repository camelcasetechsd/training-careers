<?php

/**
 * Description of Category
 *
 * 
 */
include_once('../Include/MySQLiQuery.php');

class Career
{

    protected $id;
    protected $name;
    protected $totalDuration;

    public function __construct()
    {
        $configs = include('../Include/Config.php');
        $this->connection = MySQLiQuery::getObject($configs['host'], $configs['username'], $configs['pass'], $configs['DB']);
    }

    public function getCareers()
    {
        
        if (isset($this->connection)) {
//            var_dump($this->connection->select("ASSOCIATIVE", "career"));
            return $this->connection->select("Object", "career");     
        }
        else {
            return "Database connection Error";
        }
    }

}

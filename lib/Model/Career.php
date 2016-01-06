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

    public function getCareers($id = null)
    {

        if (isset($this->connection)) {
            if (empty($id)) {
                return $this->connection->select("ASSOCIATIVE", "career");
            } else {
                return $this->connection->select("ASSOCIATIVE", "career","name, total_duration",FALSE,"id", $id, "=");
               
            }
        }
        else {
            return "Database connection Error";
        }
    }

    public function getCareerIds()
    {
        if (isset($this->connection)) {
            return $this->connection->select("ASSOCIATIVE", "career","id");
        }
        else {
            return "Database connection Error";
        }
    }

}

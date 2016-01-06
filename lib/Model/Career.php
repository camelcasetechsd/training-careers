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
                return $this->connection->select("ASSOCIATIVE", "career","id, name, SEC_TO_TIME(total_duration*60) as total_duration");
            } else {
                return $this->connection->select("ASSOCIATIVE", "career","name, SEC_TO_TIME(total_duration*60) as total_duration",FALSE,"id", $id, "=");
               
            }
        }
        else {
            return "Database connection Error";
        }
    }

    public function getCareerIds()
    {
        if (isset($this->connection)) {
            return $this->connection->select("ASSOCIATIVE", "career", "id");
        }
        else {
            return "Database connection Error";
        }
    }

    public function updateCareerDuration($operation, $oldDuration, $outlineDuration, $careerId)
    {

        if (isset($this->connection)) {
            switch ($operation) {
                case 'add':
                    return $this->connection->update('career', 'total_duration', $outlineDuration + $oldDuration, 'id', $careerId, "=");
                    break;
                case 'deduct':
                    return $this->connection->update('career', 'total_duration', $oldDuration - $outlineDuration, 'id', $careerId, "=");
                    break;
            }
        }
        else {
            return "Database connection Error";
        }
    }

    public function getCareerBy($columns, $target, $value)
    {
        if (isset($this->connection)) {

            return $this->connection->select("ASSOCIATIVE", "career", $columns, FALSE, $target, $value, "=");
        }
        else {
            return "Database connection Error";
        }
    }

}

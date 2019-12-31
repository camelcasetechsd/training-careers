<?php

/**
 * Description of Category
 *
 * 
 */
class CareerOutline
{

    protected $id;
    protected $career_id;
    protected $outline_id;

    public function __construct($config)
    {
        $this->connection = MySQLiQuery::getObject($config['host'], $config['username'], $config['pass'], $config['DB']);
    }

    public function assignOutlineToCareer($values)
    {
        $columns = array(
            'career_id',
            'outline_id'
        );

        if (isset($this->connection)) {
            $process1  = $this->connection->replace("career_outlines", $columns, $values);
            $result    = $this->calculateCareerDuration($values[0]);
            $duration  = $result[0]['total_duration'];
            $process2  = $this->connection->update("career", 'total_duration', $duration, 'id', $values[0], '=');

            if ($process1 && $process2) {
                return array('duration' => $result[0]['total_duration_formatted']);
            } else {
                return array('error' => 'Fail to update outlines or total duration');
            }
            
        } else {
            return "Database connection Error";
        }
    }

    public function unassignOutlineToCareer($values)
    {
        $columns = array(
            'career_id',
            'outline_id'
        );
        
        if (isset($this->connection)) {
            $process1  = $this->connection->delete("career_outlines", $columns, $values, "=", "and");
            $result    = $this->calculateCareerDuration($values[0]);
            $duration  = $result[0]['total_duration'];
            $process2  = $this->connection->update("career", 'total_duration', $duration, 'id', $values[0], '=');

            if ($process1 && $process2) {
                return array('duration' => $result[0]['total_duration_formatted']);
            } else {
                return array('error' => 'Fail to update outlines or total duration');
            }
            
        } else {
            return "Database connection Error";
        }
    }

    protected function calculateCareerDuration($career_id) {
        if (isset($this->connection)) {
            return $this->connection->select("ASSOCIATIVE", "outline ol JOIN career_outlines co ON ol.id = co.outline_id", "SUM(ol.duration) as total_duration, SEC_TO_TIME(SUM(ol.duration)*60) as total_duration_formatted", FALSE, "career_id", $career_id, "=");
            
        } else {
            return "Database connection Error";
        }
    }

}

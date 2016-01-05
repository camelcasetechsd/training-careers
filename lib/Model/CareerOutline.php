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
            return $this->connection->insert("career_outlines", $columns, $values);
        }
        else {
            return "Database connection Error";
        }
    }

//    public function unassignOutlineToCareer($values)
//    {
//        $columns = array(
//            'career_id',
//            'outline_id'
//        );
//
//        if (isset($this->connection)) {
//            return $this->connection->delete("career_outlines", $columns, $values, "=", "and");
//        }
//        else {
//            return "Database connection Error";
//        }
//    }

    public function unassignOutlineToCareer($values)
    {
        $columns = array(
            'career_id',
            'outline_id'
        );
        
        if (isset($this->connection)) {
            return $this->connection->delete("career_outlines",$columns, $values, "=", "and");
        }
        else {
            return "Database connection Error";
        }
    }

}

<?php

/**
 * Description of Course
 *
 * 
 */


class Outline
{

    protected $id;
    protected $name;
    protected $course_id;

    public function __construct($configs)
    {
        $this->connection = MySQLiQuery::getObject($configs['host'], $configs['username'], $configs['pass'], $configs['DB']);
    }

    public function getOutlinesByCourse($course_id)
    {
        
        if (isset($this->connection)) {
            return $this->connection->select("ASSOCIATIVE", "outline","*",FALSE,"course_id",$course_id,"=");
        }
        else {
            return "Database connection Error";
        }
    }

    public function getOutlinesByCategory($category_id)
    {
        
        if (isset($this->connection)) {
            return $this->connection->select("ASSOCIATIVE", "outline ol JOIN course crs ON ol.course_id = crs.id","ol.*",FALSE,"category_id",$category_id,"=");
        }
        else {
            return "Database connection Error";
        }
    }

}

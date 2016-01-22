<?php

/**
 * Description of Course
 *
 * 
 */

class Course
{

    protected $id;
    protected $code;
    protected $name;
    protected $category_id;

    public function __construct($configs)
    {
        $this->connection = MySQLiQuery::getObject($configs['host'], $configs['username'], $configs['pass'], $configs['DB']);
    }

    public function getCourses()
    {

        if (isset($this->connection)) {

            return $this->connection->select("ASSOCIATIVE", "course");
        }
        else {
            return "Database connection Error";
        }
    }

    public function getCourseBy($target, $value)
    {
        if (isset($this->connection)) {

            return $this->connection->select("ASSOCIATIVE", "course", "*", FALSE, $target , $value, "=");
        }
        else {
            return "Database connection Error";
        }
    }
    
}

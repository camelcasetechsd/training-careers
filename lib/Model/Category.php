<?php

/**
 * Description of Category
 *
 * 
 */

class Category
{

    protected $id;
    protected $code;
    protected $name;

    public function __construct($configs)
    {
        $this->connection = MySQLiQuery::getObject($configs['host'], $configs['username'], $configs['pass'], $configs['DB']);
    }

    public function getCategories()
    {

        if (isset($this->connection)) {

            return $this->connection->select("ASSOCIATIVE", "category");
        }
        else {
            return "Database connection Error";
        }
    }

}

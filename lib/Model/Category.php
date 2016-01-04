<?php

/**
 * Description of Category
 *
 * 
 */
include_once('../Include/MySQLiQuery.php');

class Category
{

    protected $id;
    protected $code;
    protected $name;
    protected $connection;

    public function __construct()
    {
        $configs = include('../Include/Config.php');
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

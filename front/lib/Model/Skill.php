<?php

/**
 * Description of Skill
 *
 * 
 */

class Skill
{

    protected $id;
    protected $code;
    protected $name;

    public function __construct($configs)
    {
        $this->connection = MySQLiQuery::getObject($configs['host'], $configs['username'], $configs['pass'], $configs['DB']);
    }

    public function getSkills()
    {

        if (isset($this->connection)) {

            return $this->connection->select("ASSOCIATIVE", "skill");
        }
        else {
            return "Database connection Error";
        }
    }
}

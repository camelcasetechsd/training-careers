<?php

    /**
     ********************************ORM************************************
     * This class can execute queries from parameter lists using MySQLi.<br>
     * It extends the MySQLi class with functions that can compose and execute
     * SELECT, INSERT, UPDATE, DELETE, REPLACE and HANDLER queries with parameter
     * values that define tables, fields, field values, conditions, etc..<br>
     * The class destructor function disconnects from the MySQL server
     *
     * @version 1.0
     * 
     *
     */
    class MySQLiQuery extends mysqli {
        /**
         * @var string
         * Specifies a query without a priority prefix.
         */
        const DEFAULT_PRIORITY = "";

        /**
         * @var string
         * Specifies a query as high priority.
         */
        const HIGH_PRIORITY = "HIGH_PRIORITY";

        /**
         * @var string
         * Specifies a query as low priority.
         */
        const LOW_PRIORITY = "LOW_PRIORITY";

        /**
         * @var string
         * Specifies a delayed query.
         */
        const DELAYED = "DELAYED";

        /**
         * @var string
         * Specifies the first record in a table.
         */
        const FIRST = "FIRST";

        /**
         * @var string
         * Specifies the last record in a table.
         */
        const LAST = "LAST";

        /**
         * @var string
         * Specifies all table records.
         */
        const ALL = "ALL";

        /**
         * @var string
         * Defines a bitwise AND operator.
         */
        const BW_AND = "AND";

        /**
         * @var string
         * Defines a bitwise OR operator.
         */
        const BW_OR = "OR";

        /**
         * @var string
         * Defines a bitwise XOR operator.
         */
        const BW_XOR = "XOR";

        /**
         * @var string
         * Defines a relational equal-to operator.
         */
        const R_EQUAL = "=";

        /**
         * @var string
         * Defines a relational not equal-to operator.
         */
        const R_NEQUAL = "!=";

        /**
         * @var string
         * Defines a relational less-than operator.
         */
        const R_LESS = "<";

        /**
         * @var string
         * Defines a relational greater-than operator.
         */
        const R_GREATER = ">";

        /**
         * @var string
         * Defines a relational less-than or equal-to operator.
         */
        const R_LESS_EQUAL = "<=";

        /**
         * @var string
         * Defines a relational greater-than or equal-to operator.
         */
        const R_GREATER_EQUAL = ">=";

        /**
         * @var integer
         * Specifies an associative array, representing the result set.
         */
        const ASSOCIATIVE = 0;

        /**
         * @var integer
         * Specifies a numeric array, representing the result set.
         */
        const NUMERIC = 1;

        /**
         * @var integer
         * Specifies an associative and numeric array, representing the result set.
         */
        const ASSOC_NUM = 2;

        /**
         * @var integer
         * Specifies an object whose attributes represent the names of the fields found within the result set.
         */
        const OBJECT = 3;

        /**
         * @var integer
         * Specifies a string to hold query result.
         */
        const STRING = 4;

        private $serverIP, $userName, $userPassword, $databaseName, $query, $singularReturn;

        /** Opens a new connection to the MySQL server.
         *  <br>throws <var>mysqli_connect_error</var> if the connection failed.
         *
         * @param string $serverIP MySQL server IP.
         * @param string $userName MySQL user name.
         * @param string $userPassword MySQL user password.
         * @param string $databaseName Database to work with.
         * @param boolean $singularReturn [optional]<br>Set <b>TRUE</b> to prevent <var>ASSOCIATIVE</var> <var>NUMERIC</var> <var>ASSOC_NUM</var> <var>OBJECT</var> from returning a singular array
         *
         */
         //
        
        private function __construct($serverIP, $userName, $userPassword, $databaseName, $singularReturn = FALSE) {

            $this->serverIP = $serverIP;
            $this->userName = $userName;
            $this->userPassword = $userPassword;
            $this->databaseName = $databaseName;
            $this->singularReturn = $singularReturn;

            parent::__construct($this->serverIP, $this->userName, $this->userPassword, $this->databaseName);

            if (mysqli_connect_error())
            {
                throw new Exception('(001) Connection Error (' . mysqli_connect_errno() . ') ' . mysqli_connect_error());
            }
        }
//**********************************************Singleton*******************************************************
       private static $Obj=Null;
       public static function getObject($serverIP, $userName, $userPassword, $databaseName, $singularReturn = FALSE) {
                if (self::$Obj === null) {
                      self::$Obj= new MySQLiQuery($serverIP, $userName, $userPassword, $databaseName, $singularReturn = FALSE);

			return self::$Obj;
                }
                else{
                    return self::$Obj;
                }
                
        }
  //*************************************************************************************************************************************
        /**
         * Disconnects from MySQL server when MySQLiQuery object destructs.
         */
        public function __destruct() {
            parent::close();
        }

        /**
         * Reconnect to MySQL server.
         * @return boolean Returns <b>TRUE</b> on success or <b>FALSE</b> on failure.
         */
        public function reConnect() {
            if (!parent::ping()) {
                parent::connect($this->serverIP, $this->userName, $this->userPassword, $this->databaseName);
                return TRUE;
            }
        }

        /**
         * Change currently selected database.
         * @param string $databaseName New database name.
         * @return boolean Returns <b>TRUE</b> on success or <b>FALSE</b> on failure.
         */
        public function changeDatabase($databaseName) {
            if (parent::change_user($this->userName, $this->userPassword, $databaseName)) {
                $this->databaseName = $databaseName;
                return TRUE;
            }
            return FALSE;
        }

        /**
         * Executes SELECT query.
         *
         * @param int $returnType Indicates what type of data should be produced from the result set. <br>
         * The possible values are the constants:<br>
         * <var>ASSOCIATIVE</var>, <var>NUMERIC</var>, <var>ASSOC_NUM</var>, <var>OBJECT</var> or <var>STRING</var>.
         *
         * @param string $table Table Name to perform SQL query on.
         *
         * @param string|array $targetColumn Target column in <var>$table</var> to get data from.<br>
         * The possible types are:<br>
         * <b>string</b>: Executes a query on a single column.<br>
         * <b>array</b>: Executes a query on multiple columns within the passed array.
         *
         * @param boolean $highPriority [optional] <br> If <b>TRUE</b>, gives the SELECT higher priority than a statement that updates a table. You should use this only for queries that are very fast and must be done at once.
         *
         * @param string|array $whereColumn [optional] <br>Adds a <b>WHERE</b> statement to the query, in association with <var>$whereValue</var>.<br>
         * The possible types are:<br>
         * <b>string</b>: Single column name.<br>
         * <b>array</b>: Multiple column names within the passed array.
         *
         * @param string|array $whereValue [optional]<br> Requires <var>$whereColumn</var> existence.
         * The possible types are:<br>
         * <b>string</b>: Single column value.<br>
         * <b>array</b>: Multiple column values within the passed array.
         *
         * @param string|array $relationalOperator [optional]<br>
         * Required if <b>WHERE</b> statement exists.<br>
         * The possible values are the constants:<br>
         * <var>R_EQUAL</var>, <var>R_LESS</var>, <var>R_GREATER</var>, <var>R_LESS_EQUAL</var> or <var>R_GREATER_EQUAL</var>.<br>
         * <b>string</b>: Specifies the use of a unified operator between each <var>$whereColumn</var> and <var>$whereValue</var>.<br>
         * <b>array</b>: Multiple operators to use in the order passed.
         *
         * @param string|array $logicalOperator [optional]<br>
         * Required if multiple column <b>WHERE</b> statement exists.<br>
         * The possible values for this parameter are the constants:<br>
         * <var>BW_OR</var>, <var>BW_AND</var> or <var>BW_XOR</var><br>
         * <b>string</b>: Specifies the use of a unified operator between each pair of <var>$whereColumn</var> and <var>$whereValue</var>.<br>
         * <b>array</b>: Multiple operators to use in the passed order.
         *
         * @param string $extras [optional]<br>
         * Add extra MySQL statement(s) to the query.
         *
         * @return array Returns an array if <var>$returnType</var> is <var>ASSOCIATIVE</var>, <var>NUMERIC</var> or <var>ASSOC_NUM</var>.<br><br>
         *
         * @return array Returns a two dimensional array if the result set has more than 1 record.<br><br>
         *
         * @return array Returns an object if <var>$returnType</var> is <var>OBJECT</var>
         * where the attributes of the object represent the names of the fields found within the result set.<br><br>
         *
         * @return string Returns a string if <var>$returnType</var> is <var>STRING</var>.<br><br>
         *
         * @return NULL Returns <b>NULL</b> if the results set is empty.<br><br>
         *
         * @return boolean Returns <b>FALSE</b> and triggers <var>mysqli_error</var> on failure.
         *
         * @link http://dev.mysql.com/doc/refman/5.6/en/select.html
         *
         */	
        public function select($returnType, $table, $targetColumn = "*", $highPriority = FALSE, $whereColumn = NULL, $whereValue = NULL, $relationalOperator = "", $logicalOperator = "", $extras = "") {

            if (!isset($table) || !isset($targetColumn) || !isset($returnType))
                throw new ErrorException("(100) Empty argument/s supplied.");

            if ($returnType > self::STRING || $returnType < self::ASSOCIATIVE)
                throw new ErrorException("(101) Supplied \$returnType $returnType Not Found.");

            if (is_array($targetColumn)) {
                $tmpString = "`";
                $tmpString .= implode("` , `", $targetColumn);
                $tmpString .= "`";
                $targetColumn = $tmpString;
            }

            $whereString = $this->setWhereString($whereColumn, $whereValue, $relationalOperator, $logicalOperator, 102);

            $queryString = "SELECT " . ($highPriority ? self::HIGH_PRIORITY : "") . " " . $targetColumn . " FROM " . $table . $whereString . " " . $extras . ";";

            $this->query = parent::query($queryString);

            if ($this->query) {
                $rowsNumber = $this->query->num_rows;

                if ($rowsNumber == 0)
                    return NULL;

                $queryResult = $this->setReturnObject($returnType);

                $this->query->free();

                return ($this->singularReturn || $returnType === self::STRING) ? (($rowsNumber > 1 && $returnType != self::STRING) ? $queryResult : $queryResult[0]) : $queryResult;
            } else {
                trigger_error("(107) " . $this->getError(), E_USER_ERROR);
                return FALSE;
            }
        }

        /**
         * Executes HANDLER query.
         *
         * @param int $returnType Indicates what type of data should be produced from the result set. <br>
         * The possible values are the constants:<br>
         * <var>ASSOCIATIVE</var>, <var>NUMERIC</var>, <var>ASSOC_NUM</var>, <var>OBJECT</var> or <var>STRING</var>.
         *
         * @param string $table Table Name to perform SQL query on.
         *
         * @param string|array $targetColumn Target column in <var>$table</var> to get data from.<br>
         * The possible types are:<br>
         * <b>string</b>: Executes a query on a single column.<br>
         * <b>array</b>: Executes a query on multiple columns within the passed array.
         *
         * @param string $scope [optional] <br> Scope of the query. <br>
         * The possible values are the constants: <br>
         * <var>ALL</var> Retrieve all records found limited by <var>$limit</var>.<br>
         * <var>FIRST</var> Retrieve first record(s) in <var>$table</var> limited by <var>$limit</var>.<br>
         * <var>LAST</var> Retrieve the last record(s) in <var>$table</var> based on <var>$index</var>, and limited by <var>$limit</var>.
         *
         * @param int $limit [optional] <br> Limits the result set to <var>$limit</var> <br> with default value 1.
         *
         * @param string $index [optional] <br> Required only if <var>$scope</var> = <var>LAST</var>.
         *
         * @param string|array $whereColumn [optional] <br>Adds a <b>WHERE</b> statement to the query, in association with <var>$whereValue</var>.<br>
         * The possible types are:<br>
         * <b>string</b>: Single column name.<br>
         * <b>array</b>: Multiple column names within the passed array.
         *
         * @param string|array $whereValue [optional]<br> Requires <var>$whereColumn</var> existence.
         * The possible types are:<br>
         * <b>string</b>: Single column value.<br>
         * <b>array</b>: Multiple column values within the passed array.
         *
         * @param string|array $relationalOperator [optional]<br>
         * Required if <b>WHERE</b> statement exists.<br>
         * The possible values are the constants:<br>
         * <var>R_EQUAL</var>, <var>R_LESS</var>, <var>R_GREATER</var>, <var>R_LESS_EQUAL</var> or <var>R_GREATER_EQUAL</var>.<br>
         * <b>string</b>: Specifies the use of a unified operator between each <var>$whereColumn</var> and <var>$whereValue</var>.<br>
         * <b>array</b>: Multiple operators to use in the order passed.
         *
         * @param string|array $logicalOperator [optional]<br>
         * Required if multiple column <b>WHERE</b> statement exists.<br>
         * The possible values for this parameter are the constants:<br>
         * <var>BW_OR</var>, <var>BW_AND</var> or <var>BW_XOR</var><br>
         * <b>string</b>: Specifies the use of a unified operator between each pair of <var>$whereColumn</var> and <var>$whereValue</var>.<br>
         * <b>array</b>: Multiple operators to use in the passed order.
         *
         * @param string $extras [optional]<br>
         * Add extra MySQL statement(s) to the query.
         *
         * @return array Returns an array if <var>$returnType</var> is <var>ASSOCIATIVE</var>, <var>NUMERIC</var> or <var>ASSOC_NUM</var>.<br><br>
         *
         * @return array Returns a two dimensional array if the result set has more than 1 record.<br><br>
         *
         * @return array Returns an object if <var>$returnType</var> is <var>OBJECT</var>
         * where the attributes of the object represent the names of the fields found within the result set.<br><br>
         *
         * @return string Returns a string if <var>$returnType</var> is <var>STRING</var>.<br><br>
         *
         * @return NULL Returns <b>NULL</b> if the results set is empty.<br><br>
         *
         * @return boolean Returns <b>FALSE</b> and triggers <var>mysqli_error</var> on failure.
         *
         * @link http://dev.mysql.com/doc/refman/5.6/en/handler.html
         */
        public function handler($returnType, $table, $targetColumn, $scope, $limit = 1, $index = NULL, $whereColumn = NULL, $whereValue = NULL, $relationalOperator = "", $logicalOperator = "") {

            if (!isset($table) || !isset($targetColumn) || !isset($scope) || !isset($returnType))
                throw new ErrorException("(200) Empty argument/s supplied.");

            if ($returnType > self::STRING || $returnType < self::ASSOCIATIVE)
                throw new ErrorException("(201) Supplied \$returnType $returnType Not Found.");

            if ($scope != self::ALL && $scope != self::FIRST && $scope != self::LAST)
                throw new ErrorException("(202) Supplied \$scope $scope Not Found.");

            if (isset($index) && $scope != self::LAST)
                throw new ErrorException("(203) Cannot perform a HANDLER query with \$scope of MySQLiQuery::FIRST or MySQLiQuery::ALL with defined \$index.");

            if ($scope === self::LAST && !isset($index))
                throw new ErrorException("(204) \$index is required for \$scope = MySQLiQuery::LAST.");

            $whereString = $this->setWhereString($whereColumn, $whereValue, $relationalOperator, $logicalOperator, 205);

            $queryString = "HANDLER " . $table . " OPEN;";
            $queryString .= "HANDLER " . $table . " READ " . ($scope === self::LAST ? $index . " " . self::LAST : self::FIRST) . $whereString . " LIMIT " . $limit . ";";
            $queryString .= "HANDLER " . $table . " CLOSE;";

            parent::multi_query($queryString);

            parent::next_result();

            $this->query = parent::store_result();

            if ($this->query) {
                $rowsNumber = $this->query->num_rows;

                if ($rowsNumber == 0)
                    return NULL;

                $queryResult = $targetColumn === "*" ? $this->setReturnObject($returnType) : $this->setCustomReturnObject($returnType, $targetColumn);

                $this->query->free();

                parent::next_result();

                return ($this->singularReturn || $returnType === self::STRING) ? (($rowsNumber > 1 && $returnType != self::STRING) ? $queryResult : $queryResult[0]) : $queryResult;
            } else {
                trigger_error("210) " . $this->getError(), E_USER_ERROR);
                return FALSE;
            }
        }

        /**
         * Executes INSERT query.
         *
         * @param string $table Table name to perform SQL query on.
         *
         * @param array $columns Column array to insert into.
         *
         * @param array $values Values of <var>$columns</var> to be inserted.
         *
         * @param string $priority [optional]<br>Adds a priority prefix to the query.<br>
         * The possible values are the constants:<br>
         * <var>DEFAULT_PRIORITY</var>: Executes the query without a priority prefix.<br>
         * <var>HIGH_PRIORITY</var>: Overrides the effect of the --low-priority-updates option if the server was started with that option. It also prevents concurrent inserts from being used.<br>
         * <var>DELAYED</var>: Signals for the server to put the row or rows to be inserted into a buffer, so that the client issuing the INSERT DELAYED statement can then continue immediately. If the table is in use, the server holds the rows. When the table is free,<br>
         * <var>LOW_PRIORITY</var> execution of the INSERT is delayed until no other clients are reading from the table.
         *
         * @param boolean $escapeValues [optional] <br> Indicates whether to escape <var>$Values</var> array or not.
         *
         * @return boolean Returns <b>TRUE</b> on success or <b>FALSE</b> on failure, and triggers <var>mysqli_error</var> on failure.
         *
         * @link http://dev.mysql.com/doc/refman/5.6/en/insert.html
         */
        public function insert($table, $columns, $values, $priority = self::DEFAULT_PRIORITY, $escapeValues = TRUE) {
            $queryString = "";
            $valuesString = "";
            $columnsString = "";

            if (!isset($table) || !isset($columns) || !isset($values))
                throw new ErrorException("(300) Empty argument/s supplied.");

            if (count($columns) != count($values))
                throw new ErrorException("(301) Supplied Columns not equal to Values.");

            if ($priority != self::DEFAULT_PRIORITY && $priority != self::LOW_PRIORITY && $priority != self::HIGH_PRIORITY && $priority != self::DELAYED)
                throw new ErrorException("(302) INSERT \$priority Should be either MySQLiQuery::DEFAULT_PRIORITY or MySQLiQuery::LOW_PRIORITY or MySQLiQuery::HIGH_PRIORITY or MySQLiQuery::DELAYED.");

            for ($i = 0; $i < count($columns); $i++) {
                $columnsString .= "`" . $columns[$i] . ($i < count($columns) - 1 ? "`," : "`");
                $valuesString .= "'" . ($escapeValues ? parent::real_escape_string($values[$i]) : $values[$i]) . ($i < count($values) - 1 ? "'," : "'");
            }

            $queryString = "INSERT " . $priority . " INTO `" . $table . "` (" . $columnsString . ") VALUES (" . $valuesString . ");";

            $this->query = parent::query($queryString);

            if ($this->query) {
                return $this->getAffectedRows();
            } else {
                trigger_error("(303) " . $this->getError(), E_USER_ERROR);
                return FALSE;
            }
        }

        /**
         * Replaces an entry in <var>$table</var> with given <var>$values</var>.<br>
         * Works like INSERT if no old row in the table has the same <var>$value</var> as a new row for a PRIMARY KEY or a UNIQUE index.
         *
         * @param string $table Table name to perform SQL query on.
         *
         * @param array $columns Columns array to insert into.
         *
         * @param array $values Values of <var>$columns</var> to be replaced.
         *
         * @param string $priority [optional]<br>Adds a priority prefix to the query.<br>
         * The possible values are the constants:<br>
         * <var>DEFAULT_PRIORITY</var>: Executes the query without a priority prefix.<br>
         * <var>DELAYED</var>: Signals for the server to put the row or rows to be inserted into a buffer, so that the client issuing the INSERT DELAYED statement can then continue immediately. If the table is in use, the server holds the rows. When the table is free,<br>
         * <var>LOW_PRIORITY</var> execution of the INSERT is delayed until no other clients are reading from the table.
         *
         * @param boolean $escapeValues [optional] <br> Indicates whether to escape <var>$Values</var> array or not.
         *
         * @return boolean Returns <b>TRUE</b> on success or <b>FALSE</b> with <var>mysqli_error</var> trigger on failure.
         *
         * @link http://dev.mysql.com/doc/refman/5.6/en/replace.html
         */
        public function replace($table, $columns, $values, $priority = self::DEFAULT_PRIORITY, $escapeValues = TRUE) {
            $queryString = "";
            $valuesString = "";
            $columnsString = "";

            if (!isset($table) || !isset($columns) || !isset($values))
                throw new ErrorException("(400) Empty argument/s supplied.");

            if (count($columns) != count($values))
                throw new ErrorException("(401) Supplied Columns not equal to Values.");

            if ($priority != self::DEFAULT_PRIORITY && $priority != self::LOW_PRIORITY && $priority != self::DELAYED)
                throw new ErrorException("(402) REPLACE \$priority Should be either MySQLiQuery::DEFAULT_PRIORITY or MySQLiQuery::LOW_PRIORITY or MySQLiQuery::DELAYED.");

            for ($i = 0; $i < count($columns); $i++) {
                $columnsString .= "`" . $columns[$i] . ($i < count($columns) - 1 ? "`," : "`");
                $valuesString .= "'" . ($escapeValues ? parent::real_escape_string($values[$i]) : $values[$i]) . ($i < count($values) - 1 ? "'," : "'");
            }

            $queryString = "REPLACE " . $priority . " INTO `" . $table . "` (" . $columnsString . ") VALUES (" . $valuesString . ");";

            $this->query = parent::query($queryString);

            if ($this->query) {
                return $this->getAffectedRows();
            } else {
                trigger_error("(403) " . $this->getError(), E_USER_ERROR);
                return FALSE;
            }
        }

        /**
         * Execute DELETE query.
         *
         * @param string $table Table name to perform SQL query on.
         *
         * @param string|array $whereColumn [optional] <br>Adds a <b>WHERE</b> statement to the query, in association with <var>$whereValue</var>.<br>
         * The possible types are:<br>
         * <b>string</b>: Single column name.<br>
         * <b>array</b>: Multiple column names within the passed array.
         *
         * @param string|array $whereValue [optional]<br> Requires <var>$whereColumn</var> existence.
         * The possible types are:<br>
         * <b>string</b>: Single column value.<br>
         * <b>array</b>: Multiple column values within the passed array.
         *
         * @param string|array $relationalOperator [optional]<br>
         * Required if <b>WHERE</b> statement exists.<br>
         * The possible values are the constants:<br>
         * <var>R_EQUAL</var>, <var>R_LESS</var>, <var>R_GREATER</var>, <var>R_LESS_EQUAL</var> or <var>R_GREATER_EQUAL</var>.<br>
         * <b>string</b>: Specifies the use of a unified operator between each <var>$whereColumn</var> and <var>$whereValue</var>.<br>
         * <b>array</b>: Multiple operators to use in the order passed.
         *
         * @param string|array $logicalOperator [optional]<br>
         * Required if multiple column <b>WHERE</b> statement exists.<br>
         * The possible values for this parameter are the constants:<br>
         * <var>BW_OR</var>, <var>BW_AND</var> or <var>BW_XOR</var><br>
         * <b>string</b>: Specifies the use of a unified operator between each pair of <var>$whereColumn</var> and <var>$whereValue</var>.<br>
         * <b>array</b>: Multiple operators to use in the passed order.
         *
         * @param boolean $quick [optional]<br>for MyISAM tables, if <b>TRUE</b>, the storage engine does not merge index leaves during delete, which may speed up some kinds of delete operations.
         *
         * @param boolean $lowPriority [optional]<br>If <b>TRUE</b>, the server delays execution of the <b>DELETE</b> until no other clients are reading from the table.
         *
         * @param string $extras [optional]<br>
         * Adds extra MySQL statement(s) to the query.
         *
         * @return boolean Returns <b>TRUE</b> on success or <b>FALSE</b> with <var>mysqli_error</var> trigger on failure.
         *
         * @link http://dev.mysql.com/doc/refman/5.6/en/delete.html
         *
         */
        public function delete($table, $whereColumn, $whereValue, $relationalOperator = "", $logicalOperator = "", $quick = FALSE, $lowPriority = FALSE, $extras = "") {
            if (!isset($table))
                throw new ErrorException("(500) Empty argument/s supplied.");

            $whereString = $this->setWhereString($whereColumn, $whereValue, $relationalOperator, $logicalOperator, 501);

            $queryString = "DELETE " . ($lowPriority ? self::LOW_PRIORITY : "") . " " . ($quick ? "QUICK" : "") . " FROM " . $table . $whereString . " " . $extras . ";";

            $this->query = parent::query($queryString);

            if ($this->query) {
                return $this->getAffectedRows();
            } else {
                trigger_error("(506) " . $this->getError(), E_USER_ERROR);
                return FALSE;
            }
        }

        /**
         * execute UPDATE query
         *
         * @param string $table Table name to perform sql query on.
         *
         * @param string|array $targetColumn Target column in <var>$table</var> to get data from.<br>
         * The possible types are:<br>
         * <b>string</b>: Executes a query on a single column.<br>
         * <b>array</b>: Executes a query on a multiple columns within the passed array.
         *
         * @param string|array $newValue New values to be updated in <var>$column</var>.<br>
         * The possible types are:<br>
         * <b>string</b>: Single column value.<br>
         * <b>array</b>: Multiple column values within the passed array.
         *
         * @param string|array $whereColumn [optional] <br>Adds a <b>WHERE</b> statement to the query, in association with <var>$whereValue</var>.<br>
         * The possible types are:<br>
         * <b>string</b>: Single column name.<br>
         * <b>array</b>: Multiple column names within the passed array.
         *
         * @param string|array $whereValue [optional]<br> Requires <var>$whereColumn</var> existence.
         * The possible types are:<br>
         * <b>string</b>: Single column value.<br>
         * <b>array</b>: Multiple column values within the passed array.
         *
         * @param string|array $relationalOperator [optional]<br>
         * Required if <b>WHERE</b> statement exists.<br>
         * The possible values are the constants:<br>
         * <var>R_EQUAL</var>, <var>R_LESS</var>, <var>R_GREATER</var>, <var>R_LESS_EQUAL</var> or <var>R_GREATER_EQUAL</var>.<br>
         * <b>string</b>: Specifies the use of a unified operator between each <var>$whereColumn</var> and <var>$whereValue</var>.<br>
         * <b>array</b>: Multiple operators to use in the order passed.
         *
         * @param string|array $logicalOperator [optional]<br>
         * Required if multiple column <b>WHERE</b> statement exists.<br>
         * The possible values for this parameter are the constants:<br>
         * <var>BW_OR</var>, <var>BW_AND</var> or <var>BW_XOR</var><br>
         * <b>string</b>: Specifies the use of a unified operator between each pair of <var>$whereColumn</var> and <var>$whereValue</var>.<br>
         * <b>array</b>: Multiple operators to use in the passed order.
         *
         * @param boolean $lowPriority [optional]<br>if <b>TRUE</b>, the server delays execution of the <b>DELETE</b> until no other clients are reading from the table.
         *
         * @param string $extras [optional] <br>
         * Adds extra MySQL statement(s) to the query.
         *
         * @param boolean $escapeValues [optional] <br> Indicates whether to escape <var>$values</var> array or not.
         *
         * @return boolean Returns <b>TRUE</b> on success or <b>FALSE</b> with <var>mysqli_error</var> trigger on failure.
         *
         * @link http://dev.mysql.com/doc/refman/5.6/en/update.html
         *
         */
        public function update($table, $targetColumn, $newValue, $whereColumn, $whereValue, $relationalOperator = "", $logicalOperator = "", $lowPriority = FALSE, $escapeValues = TRUE, $extras = "") {
            $updateString = "";

            if (!isset($table))
                throw new ErrorException("(600) Empty argument/s supplied.");

            $whereString = $this->setWhereString($whereColumn, $whereValue, $relationalOperator, $logicalOperator, 601);

            if (is_array($targetColumn) && is_array($newValue)) {
                if (count($targetColumn) != count($newValue))
                    throw new ErrorException("(603) Supplied Columns not equal to Values.");

                for ($i = 0; $i < count($targetColumn); $i++) {
                    $updateString .= "`" . $targetColumn[$i] . "` = '" . ($escapeValues ? parent::real_escape_string($newValue[$i]) : $newValue[$i]) . "'" . ($i < count($newValue) - 1 ? ", " : "");
                }
            } else {
                $updateString .= "`" . $targetColumn . "` = '" . ($escapeValues ? parent::real_escape_string($newValue) : $newValue) . "'";
            }

            $queryString = "UPDATE " . ($lowPriority ? self::LOW_PRIORITY : "") . " " . $table . " SET " . $updateString . $whereString . $extras . ";";

            $this->query = parent::query($queryString);

            if ($this->query) {
                return $this->getAffectedRows();
            } else {
                trigger_error("(607) " . $this->getError(), E_USER_ERROR);
                return FALSE;
            }
        }

        /**
         * Executes SQL query
         * @param type $sqlQuery Query to be executed
         * @return boolean Returns <b>TRUE</b> on success or <b>FALSE</b> with <var>mysqli_error</var> trigger on failure. Returns a result object SELECT, SHOW, DESCRIBE or EXPLAIN.
         */
        public function execute($sqlQuery) {
            $this->query = parent::query($sqlQuery);
            if ($this->query) {
                return $this->query;
            } else {
                trigger_error("(700) " . $this->getError(), E_USER_ERROR);
                return FALSE;
            }
        }

        /**
         * Constructs a string representation of a WHERE statement.
         * @access private
         * @param string|array $whereColumn
         * @param string|array $whereValue
         * @param string|array $relationalOperator
         * @param string|array $logicalOperator
         * @param int $errCode
         * @return string Returns the constructed strings
         */
        private function setWhereString($whereColumn, $whereValue, $relationalOperator, $logicalOperator, $errCode) {
            $whereString = " ";

            if (is_array($whereColumn) && is_array($whereValue)) {

                if (count($whereColumn) != count($whereValue))
                    throw new ErrorException("(" . $errCode . ") Supplied Columns not equal to Values.");

                if (!isset($logicalOperator))
                    throw new ErrorException("(" . $errCode + 1 . ") Logical Operator Cannot be empty while using multi-where statement.");

                if (is_array($logicalOperator) && count($logicalOperator) != count($whereColumn) - 1)
                    throw new ErrorException("(" . $errCode + 2 . ") Supplied \$logicalOperator array size is not sufficient.");

                if (is_array($relationalOperator) && count($relationalOperator) != count($whereColumn))
                    throw new ErrorException("(" . $errCode + 3 . ") Supplied \$relationalOperator array size is not sufficient.");

                for ($i = 0; $i < count($whereColumn); $i++) {
                    $whereString .= $i == 0 ? "WHERE `" . $whereColumn[$i] . "`" . (is_array($relationalOperator) ? $relationalOperator[$i] : $relationalOperator) . "'" . parent::real_escape_string($whereValue[$i]) . "'" : " " . (is_array($logicalOperator) ? $logicalOperator[$i - 1] : $logicalOperator) . " `" . $whereColumn[$i] . "`" . (is_array($relationalOperator) ? $relationalOperator[$i] : $relationalOperator) . "'" . parent::real_escape_string($whereValue[$i]) . "'";
                }
            } else if ($whereColumn != NULL && $whereValue != NULL) {
                if ($relationalOperator === "")
                    throw new ErrorException("(" . $errCode + 4 . ") Relational Operator Cannot be empty.");
                $whereString .= "WHERE `" . $whereColumn . "`" . $relationalOperator . "'" . parent::real_escape_string($whereValue) . "'";
            }

            return $whereString;
        }

        /**
         * Constructs a MySQLiQuery return object.
         * @access private
         * @param int $returnType
         * @return mixed Returns the constructed object.
         */
        private function setReturnObject($returnType) {
            $queryResult = array();
            $retrunCounter = 0;

            switch ($returnType) {
                case self::ASSOCIATIVE:
                    while ($row = $this->query->fetch_assoc()) {
                        $queryResult[$retrunCounter] = $row;
                        $retrunCounter++;
                    }
                    break;

                case self::NUMERIC:
                    while ($row = $this->query->fetch_array(MYSQLI_NUM)) {
                        $queryResult[$retrunCounter] = $row;
                        $retrunCounter++;
                    }
                    break;

                case self::ASSOC_NUM:
                    while ($row = $this->query->fetch_array()) {
                        $queryResult[$retrunCounter] = $row;
                        $retrunCounter++;
                    }
                    break;

                case self::OBJECT:
                    while ($row = $this->query->fetch_object()) {
                        $queryResult[$retrunCounter] = $row;
                        $retrunCounter++;
                    }
                    break;

                case self::STRING:
                    $row = $this->query->fetch_array(MYSQLI_NUM);
                    $queryResult[$retrunCounter] = $row[0];
                    break;

                default:
                    break;
            }

            return $queryResult;
        }

        /**
         * Constructs a custom MySQLiQuery return object.
         * @access private
         * @param int $returnType
         * @param string|array $targetColumn
         * @return mixed Returns the constructed object.
         */
        private function setCustomReturnObject($returnType, $targetColumn) {
            $resultSet = $this->setReturnObject(self::ASSOCIATIVE);

            $targetColumn = is_array($targetColumn) ? $targetColumn : array($targetColumn);

            $returnObject = array();

            for ($i = 0; $i < count($resultSet); $i++) {

                if ($returnType === self::OBJECT)
                    $returnObject[$i] = new stdClass();

                for ($j = 0; $j < count($targetColumn); $j++) {
                    switch ($returnType) {
                        case self::ASSOCIATIVE:
                            $returnObject[$i][$targetColumn[$j]] = $resultSet[$i][$targetColumn[$j]];
                            break;

                        case self::NUMERIC:
                            $returnObject[$i][$j] = $resultSet[$i][$targetColumn[$j]];
                            break;

                        case self::ASSOC_NUM:
                            $returnObject[$i][$j] = $resultSet[$i][$targetColumn[$j]];
                            $returnObject[$i][$targetColumn[$j]] = $resultSet[$i][$targetColumn[$j]];
                            break;

                        case self::OBJECT:
                            $returnObject[$i]->$targetColumn[$j] = $resultSet[$i][$targetColumn[$j]];
                            break;

                        case self::STRING:
                            $returnObject[$i] = $resultSet[$i][$targetColumn[$j]];
                            break;

                        default:
                            break;
                    }
                }
            }
            return $returnObject;
        }

        /**
         * Returns a string representation of the last error to occur for the database connection. Returns an empty string if no error has occured.
         * @access private
         * @return string
         */
        private function getError() {
            return mysqli_error($this);
        }

        /**
         * Returns the number of rows affected by the last INSERT, UPDATE, or DELETE query associated with the provided link parameter. Returns -1 if the last query was invalid.
         * @access private
         * @return int
         */
        private function getAffectedRows() {
            return mysqli_affected_rows($this);
        }

    }
?>

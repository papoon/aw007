<?php
    include_once '../database/dbUtils.php';

    class DbConnector {

        private $hostName;
        private $userName;
        private $password;
        private $dbName;
        private $connection;
        private $sqlQuery;
        public $dataset;

        const DEFAULT_HOST = "localhost";
        const DEFAULT_USER = "aw007";
        const DEFAULT_PASS = "aw007";
        const DEFAULT_DB = "aw007";

        //generic construct
        public function __construct($host, $user, $pass, $db) {
            $this->hostName = $host;
            $this->userName = $user;
            $this->password = $pass;
            $this->dbName = $db;
            $this->connection = NULL;
            $this->sqlQuery = NULL;
            $this->dataSet = NULL;
        }

        //default connection auxiliar function
        public static function defaultConnection() {
            $instance = new self(DbConnector::DEFAULT_HOST, DbConnector::DEFAULT_USER,
                                 DbConnector::DEFAULT_PASS, DbConnector::DEFAULT_DB);
            return $instance;
        }

        //connect function, returns link to the db
        //(MUST BE CALLED BEFORE ANY OPERATION IS MADE)
        public function connect()  {
            $this->connection = mysqli_connect($this->hostName, $this->userName,
                                               $this->password, $this->dbName);
            return $this->connection;
        }

        //respective disconnect function
        public function disconnect() {
            mysqli_close($this->connection);
        }

        //OPERATIONS
        function selectAll($tableName,$limit=NULL)  {
            $this->sqlQuery = 'SELECT * FROM '.$this->dbName.'.'.$tableName;
            if(!is_null($limit)) {
              $this->sqlQuery .= ' LIMIT '.$limit;
            }
            //echo $this->sqlQuery;
            $this->dataSet = mysqli_query($this->connection, $this->sqlQuery);
            return $this->dataSet;
        }

        function selectWhere($tableName, $columnName, $operator, $value, $valueType, $limit=NULL)   {
            $this->sqlQuery = 'SELECT * FROM '.$tableName.' WHERE '.$columnName.' '.$operator.' ';
            if($valueType == 'int') {
                $this->sqlQuery .= $value;
            }
            else if($valueType == 'char')   {
                $this->sqlQuery .= "'".$value."'";
            }
            if(!is_null($limit)) {
              $this->sqlQuery .= ' LIMIT '.$limit;
            }
            //echo $this->sqlQuery;
            $this->dataSet = mysqli_query($this->connection, $this->sqlQuery);
            $this->sqlQuery = NULL;
            return $this->dataSet;
        }

        function selectColumnAll($tableName, $columnName) {
          $this->sqlQuery = 'SELECT '.$columnName.' FROM '.$this->dbName.'.'.$tableName;
          //echo $this->sqlQuery;
          $this->dataSet = mysqli_query($this->connection, $this->sqlQuery);
          return $this->dataSet;
        }

        function selectCountAll($tableName)   {
            $this->sqlQuery = 'SELECT COUNT(*) FROM '.$tableName;
            //echo $this->sqlQuery;
            $this->dataSet = mysqli_query($this->connection, $this->sqlQuery);
            $this->sqlQuery = NULL;
            return $this->dataSet;
        }

        function selectCountWhere($tableName, $columnName, $operator, $value, $valueType)   {
            $this->sqlQuery = 'SELECT COUNT(*) FROM '.$tableName.' WHERE '.$columnName.' '.$operator.' ';
            if($valueType == 'int') {
                $this->sqlQuery .= $value;
            }
            else if($valueType == 'char')   {
                $this->sqlQuery .= "'".$value."'";
            }
            //echo $this->sqlQuery;
            $this->dataSet = mysqli_query($this->connection, $this->sqlQuery);
            $this->sqlQuery = NULL;
            return $this->dataSet;
        }

        function insertInto($tableName, $values) {
            $queryValuesStr = getValuesStr($tableName);
            $this->sqlQuery = 'INSERT INTO '.$tableName.$queryValuesStr.' VALUES (';
            $i = 0;
            while($values[$i]["val"] != NULL && $values[$i]["type"] != NULL)    {
                if($values[$i]["type"] == "char")   {
                    $this->sqlQuery .= "'";
                    $this->sqlQuery .= $values[$i]["val"];
                    $this->sqlQuery .= "'";
                }
                else if($values[$i]["type"] == 'int')   {
                    $this->sqlQuery .= $values[$i]["val"];
                }
                $i++;
                if($values[$i]["val"] != NULL)  {
                    $this->sqlQuery .= ',';
                }
            }
            $this->sqlQuery .= ')';
            //echo $this->sqlQuery;
            mysqli_query($this->connection, $this->sqlQuery);
            return $this -> sqlQuery;
        }


    }
?>

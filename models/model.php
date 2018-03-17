<?php

    require_once '../database/dbConnector.php';
    require_once '../database/dbUtils.php';
    
    class Model{
        public $connector;
        public function __construct(){

            $this->connector = DbConnector::defaultConnection();
            $this->connector->connect();
        }
        public function __destruct(){
            $this->connector->disconnect();
        }
    }

?>
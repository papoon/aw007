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
            #$this->connector->disconnect();
        }

        protected function utf8magic($data) {
          array_walk_recursive($data, function(&$value) {
            $value = utf8_encode($value);
          });
          return $data;
        }
    }

?>

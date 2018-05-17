<?php

    require_once '../database/dbConnector.php';
    require_once '../database/dbUtils.php';
    require_once '../utils/Encoding.php';
    use \ForceUTF8\Encoding;

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
          $version = PHP_VERSION_ID;
          $needsMagic = $version < 50500;
          if ($needsMagic) {
            array_walk_recursive($data, function(&$value) {
              $value = Encoding::toUTF8($value);
            });
          }
          return $data;
        }
    }

?>

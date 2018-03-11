<?php
    include_once '../database/dbConnector.php';

    class DataCollector {

      private $connector;
      private $dbLink;
      private $numberDiseases;
      private $numberElements;

      //generic construct
      #$numberElements is the number of elements of each type that should
      #be able to be shown in the application (e.g. photos, tweets) for each
      #disease;
      public function __construct($numberDiseases, $numberElements) {
          $this->connector = DbConnector::defaultConnection();
          $this->dbLink = $this->connector->connect();
          $this->numberDiseases = $numberDiseases;
          $this->numberElements = $numberElements;
      }

      public function startCollection() {


      }

    }
?>

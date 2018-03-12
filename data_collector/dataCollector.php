<?php
    include_once '../database/dbConnector.php';
    include_once '../database/dbUtils.php';
    include_once "../webservices/dbpediaDiseases.php";
    //include_once "../webservices/pubmedSearch.php";
    //include_once "../webservices/pubmedFeach.php";
    //include_once "../webservices/flickr.php";
    //include_once "../webservices/twitterSearch.php";
    //include_once "../webservices/twitterEmbed.php";

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

          //get current count of diseases
          $countDiseases = getCountResult($this->connector->selectCountAll(TABLE_DISEASE));

          //if we do not have enough diseases in the database, we get some
          if($countDiseases < $this->numberDiseases) {
            //get the information for the needed number of diseases
            $dbPediaDiseases = new DBPediaDiseases($this->numberDiseases);
            $diseases = $dbPediaDiseases->getDiseases();
            //insert disease information in the database only if it is not there
            foreach($diseases as $disease){
                var_dump($disease);
                echo '*****';
            }

          }

          $this->connector->disconnect();
      }

    }
?>

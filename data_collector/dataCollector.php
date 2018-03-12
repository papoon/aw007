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
      //$numberElements is the number of elements of each type that should
      //be able to be shown in the application (e.g. photos, tweets) for each
      //disease;
      public function __construct($numberDiseases, $numberElements) {
          $this->connector = DbConnector::defaultConnection();
          $this->dbLink = $this->connector->connect();
          $this->numberDiseases = $numberDiseases;
          $this->numberElements = $numberElements;
      }

      public function startCollectionAll() {

          //get current diseases
          $currentDiseases = $this->connector->selectColumnAll(TABLE_DISEASE, 'id');
          $countDiseases = getNumberRows($currentDiseases);
          $arrayDiseaseIds = convertDatasetToArray($currentDiseases);

          if($countDiseases > 0) {
            echo 'Current diseases: ';
            printColumn($currentDiseases, 'id');
          }
          else {
            echo 'No diseases found. <br/>';
          }

          //get the information for the needed number of diseases
          $dbPediaDiseases = new DBPediaDiseases($this->numberDiseases);
          $diseases = $dbPediaDiseases->getDiseases();
          $newDiseases = array();
          $existingDiseases = array();
          //insert disease information in the database only if it is not there
          foreach($diseases as $disease){
              //if the retrieved disease is not in the database
              if(!in_array($disease['id']['value'], $arrayDiseaseIds)) {
                //save its information on the database
                $currentDate = new DateTime();
                $currentDateStr = $currentDate->format('Y-m-d H:i:s');

                //WIP
                $values = array($disease['label']['value'],
                                $disease['wikiPageID']['value'],
                                $disease['abstract']['value'],
                                $currentDateStr,
                                $currentDateStr);

                $toInsert = createInsertArray(TABLE_DISEASE, $values);
              }
          }



          $this->connector->disconnect();
      }

    }
?>

<?php
    require_once 'controller.php';
    require_once '../webservices/api/rest/diseasesRestHandler.php';

    class Rest extends Controller{
        public function __construct(){
            parent::__construct();
        }
        public function diseases(){

            // to handle REST Url /diseases/list/
		    $diseasesRestHandler = new DiseasesRestHandler();
		    $diseasesRestHandler->getAllDiseases();
           
        }
    }


?>
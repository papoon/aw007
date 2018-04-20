<?php
    require_once 'controller.php';
    require_once '../webservices/api/rest/diseasesRestHandler.php';

    class Rest extends Controller{
        public function __construct(){
            parent::__construct();
        }
        public function diseases($id=0){

            if($id == 0){
                // to handle REST Url /diseases/
		        $diseasesRestHandler = new DiseasesRestHandler();
                $diseasesRestHandler->getAllDiseases();
            }else{
                // to handle REST Url /diseases/id
		        $diseasesRestHandler = new DiseasesRestHandler();
                $diseasesRestHandler->getDisease($id);
            }
            
            

           
        }
    }


?>
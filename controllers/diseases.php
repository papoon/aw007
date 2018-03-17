<?php

    require_once 'controller.php';
    require_once '../models/disease.php';

    class Diseases extends Controller{
        private $desiase;
        public function __construct(){
            parent::__construct();

            $this->desiase = new Disease();
        }
        public function index($id=0){
            if($id == 0){

                $diseases = $this->desiase->getDiseases();
                $this->view->message =  array('diseases' => $diseases);

                
                $this->view->render('diseases/index.html');
            }
            else{
                $disease = $this->desiase->getDisease($id);
                $this->view->message =  array('disease' => $disease);
                $this->view->render('diseases/disease.html');
            }
            
        }
    }
?>
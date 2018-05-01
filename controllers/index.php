<?php
    require_once 'controller.php';
    require_once '../models/disease.php';

    class Index extends Controller{
        public function __construct(){
            parent::__construct();
            
            $this->disease = new Disease();
        }
        public function index(){

            $diseases = $this->disease->getDiseases();

            $this->view->message = array('diseases' => $diseases);
            $this->view->render('index/index.html.twig');
           
        }
    }


?>
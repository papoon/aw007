<?php
    require_once 'controller.php';
    require_once '../models/disease.php';
    require_once '../models/statistics.php';

    class Index extends Controller{
        public function __construct(){
            parent::__construct();
        }
        public function index(){

            $this->disease = new Disease();
            $diseases = $this->disease->getDiseases();

            $this->statistics = new Statistic();
            $statistics = $this->statistics->getAll();

            $this->view->message = array('diseases'=>$diseases,'statistics'=>$statistics);
            $this->view->render('index/index.html.twig');
           
        }
    }


?>
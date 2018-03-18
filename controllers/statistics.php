<?php

    require_once 'controller.php';
    require_once '../models/statistics.php';

    class Statistics extends Controller{
        private $statistics;
        public function __construct(){
            parent::__construct();

            $this->statistics = new Statistic();
        }
        public function index($id=0){
            if($id == 0){

                $statistics = $this->statistics->getAll();

                $this->view->message =  array('statistics' => $statistics);

                $this->view->render('statistics/index.html');
            }
            else{

                $statistics = $this->statistics->getDisease($id);
                $this->view->message =  array('statistics' => $statistics);
                $this->view->render('statistics/statistic.html');
            }
            
        }
    }

?>
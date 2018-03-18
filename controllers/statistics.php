<?php

    require_once 'controller.php';
    require_once '../models/statistics.php';

    class Statistics extends Controller{
        private $statistics;
        public function __construct(){
            parent::__construct();

            $this->statistics = new Statistic();
            $this->statistic = new Statistic();
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

            /*-*  Total numbers - Counts  *-*/
            $nrDiseases = $this->statistic->getNrOfDiseases();
            $nrTweets = $this->statistic->getNrOfTweets();
            $nrPhotos= $this->statistic->getNrOfPhotos();
            $nrArticles = $this->statistic->getNrOfArticles();
            $nrAuthors= $this->statistic->getNrOfAuthors();            
        
            $nrTweetsByDisease = $this->statistic->getNrTweetsById($id);
            
        
            $this->view->message =  array(
                                        'nrDiseases' => $nrDiseases,
                                        'nrPhotos' => $nrPhotos,
                                        'nrTweets' => $nrTweets,
                                        'nrArticles' => $nrArticles,
                                        'nrAuthors' => $nrAuthors,
                                        'nrTweetsByDisease' => $nrTweetsByDisease

                                    ); 

            $this->view->render('statistics/statistics.html');
         }

    }
?>
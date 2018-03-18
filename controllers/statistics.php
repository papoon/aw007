<?php

    require_once 'controller.php';
    require_once '../models/statistic.php';
 
    class Statistics extends Controller{
        private $statistic;
        public function __construct(){
            
            parent::__construct();

            $this->statistic = new Statistic();
        }
        public function index($id=0){

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
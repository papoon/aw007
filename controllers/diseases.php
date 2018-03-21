<?php

    require_once 'controller.php';
    require_once '../models/disease.php';
    require_once '../models/articles.php';
    require_once '../models/photos.php';
    require_once '../models/tweets.php';

    class Diseases extends Controller{
        private $disease;
        private $article;
        private $photos;
        private $tweets;

        public function __construct(){
            parent::__construct();

            $this->disease = new Disease();
            
        }
        public function index($id=0){
            if($id == 0){

                $diseases = $this->disease->getDiseases();
                $this->view->message =  array('diseases' => $diseases);

                $this->view->render('diseases/index.html');
            }
            else{

                $this->article = new Article();
                $this->photos = new Photos();
                $this->tweets = new Tweets();

                $disease = $this->disease->getDisease($id);

                $articlesDisease = $this->article->getArticlesDisease($id);

                $photosDisease = $this->photos->getPhotosDisease($id);

                $tweetsDisease = $this->tweets->getTweetsDisease($id);

                unset($this->disease);
                unset($this->article);
                unset($this->photos);
                unset($this->tweets);

                $data = array(
                    'disease'=>$disease,
                    'articlesDisease' => $articlesDisease,
                    'photosDisease'=>$photosDisease,
                    'tweetsDisease'=>$tweetsDisease
                );

                $this->view->message =  array('data' => $data);
                $this->view->render('diseases/disease.html');
            }
            
        }
    }

?>
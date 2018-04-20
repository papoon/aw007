<?php
    require_once 'controller.php';
    require_once '../webservices/api/rest/diseasesRestHandler.php';
    require_once '../webservices/api/rest/articlesRestHandler.php';
    require_once '../webservices/api/rest/photosRestHandler.php';
    require_once '../webservices/api/rest/tweetsRestHandler.php';

    class Rest extends Controller{
        public function __construct(){
            parent::__construct();
        }

        #diseases/?
        public function diseases($id=0){

            if($id == 0){
                // to handle REST Url /diseases/
		        $desisesRestHandler = new DiseasesRestHandler();
                $desisesRestHandler->getAllDiseases();
            }else{
                // to handle REST Url /diseases/id
		        $desisesRestHandler = new DiseasesRestHandler();
                $desisesRestHandler->getDisease($id);
            }
        }
        #/articles/?
        public function articles($id=0){
            if($id == 0){
                // to handle REST Url /articles/
		        $articlesRestHandler = new ArticlesRestHandler();
                $articlesRestHandler->getAllArticles();
            }else{
                // to handle REST Url /articles/id
		        $articlesRestHandler = new ArticlesRestHandler();
                $articlesRestHandler->getArticle($id);
            }
            
        }
        #/photos/?
        public function photos($id=0){
            if($id == 0){
                // to handle REST Url /photos/
		        $photosRestHandler = new PhotosRestHandler();
                $photosRestHandler->getAllPhotos();
            }else{
                // to handle REST Url /photos/disease_id
		        $photosRestHandler = new PhotosRestHandler();
                $photosRestHandler->getPhotosDisease($id);
            }
            
        }
        #/tweets/?
        public function tweets($id=0){
            if($id == 0){
                // to handle REST Url /tweets/
		        $tweetsRestHandler = new TweetsRestHandler();
                $tweetsRestHandler->getAllTweets();
            }else{
                // to handle REST Url /tweets/disease_id
		        $tweetsRestHandler = new TweetsRestHandler();
                $tweetsRestHandler->getTweetsDisease($id);
            }
            
        }
    }


?>
<?php
    require_once 'controller.php';
    require_once '../webservices/api/rest/diseasesRestHandler.php';
    require_once '../webservices/api/rest/articlesRestHandler.php';
    require_once '../webservices/api/rest/photosRestHandler.php';
    require_once '../webservices/api/rest/tweetsRestHandler.php';
    require_once '../webservices/api/rest/statisticsRestHandler.php';
    require_once '../webservices/api/rest/feedbackRestHandler.php';

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

                if(isset($_GET) && array_key_exists('metadata',$_GET)){


                    $with_metadata = $_GET['metadata'];

                    
                    if($with_metadata == 'true'){
                        
                        // to handle REST Url /diseases/id?metadata=true
		                $desisesRestHandler = new DiseasesRestHandler();
                        $desisesRestHandler->getDiseaseMetadata($id);
                        
                
                        
                    }
                    else{
                        // to handle REST Url /diseases/id
		                $desisesRestHandler = new DiseasesRestHandler();
                        $desisesRestHandler->getDisease($id);
                    }
                }
                else{

                    // to handle REST Url /diseases/id
		            $desisesRestHandler = new DiseasesRestHandler();
                    $desisesRestHandler->getDisease($id);
                }   
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
        #/statistics
        public function statistics($id=0){
            if($id == 0){
                // to handle REST Url /tweets/
		        $statisticsRestHandler = new StatisticsRestHandler();
                $statisticsRestHandler->getStatistics();
            }
        }

        public function feedback($id=0){

            if($id == 0){
                // to handle REST Url /feedback/article
		        $feedbackRestHandler = new FeedbackRestHandler();
                $feedbackRestHandler->rating();
            }
            else{
                $feedbackRestHandler = new FeedbackRestHandler();
                $feedbackRestHandler->rating();
            }
        }
        public function ratingArticle($id){

            $feedbackRestHandler = new FeedbackRestHandler();
            $feedbackRestHandler->ratingArticle($id);
            
        }
        public function ratingDisease($id){

            $feedbackRestHandler = new FeedbackRestHandler();
            $feedbackRestHandler->ratingDisease($id);
            
        }
        public function commentArticle($id){

            $feedbackRestHandler = new FeedbackRestHandler();
            $feedbackRestHandler->commentArticle($id);
            
        }
        public function commentDisease($id){

            $feedbackRestHandler = new FeedbackRestHandler();
            $feedbackRestHandler->commentDisease($id);
            
        }

        public function ratingDiseaseArticle($id){

            $feedbackRestHandler = new FeedbackRestHandler();
            $feedbackRestHandler-> ratingDiseaseArticle($id);

        }




    }


?>
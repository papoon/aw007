<?php
    require_once 'model.php';
    
    class Statistic extends Model{

        public function __construct(){
            parent::__construct();
        }

        public function getNrOfDiseases(){

            $data = $this->connector->selectCountAll(TABLE_DISEASE)->fetch_assoc();
            return $data['COUNT(*)'];  
                        
        }
        
        public function getNrOfTweets(){

            $data = $this->connector->selectCountAll(TABLE_TWEETS)->fetch_assoc();
            return $data['COUNT(*)'];  
                        
        }
        
        public function getNrOfPhotos(){

            $data = $this->connector->selectCountAll(TABLE_PHOTOS)->fetch_assoc();
            return $data['COUNT(*)'];  
                        
        }
        
        
        public function getNrOfArticles(){

            $data = $this->connector->selectCountAll(TABLE_ARTICLE)->fetch_assoc();
            return $data['COUNT(*)'];  
                        
        }
        
        public function getNrOfAuthors(){

            $data = $this->connector->selectCountAll(TABLE_AUTHOR)->fetch_assoc();
            return $data['COUNT(*)'];  
                        
        }
                
        
        public function getNrTweetsById($id){
            
            $id = mysql_real_escape_string($id);
            $data = $this->connector->selectCountWhere(TABLE_TWEETS,'id','=',$id,'int')->fetch_assoc();
            return $data;
            
        }

        /****  
        
            TODO : 
            - get number of photos/articles per disease; 
            - average of tweets/photos/articles per disease; 
            - top 10 diseases in photos, tweets and articles; 
        
        ***/ 
        
        
        
        
    }


?>
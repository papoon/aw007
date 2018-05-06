<?php
    require_once 'model.php';
    class Feedback extends Model{

        public function __construct(){
            parent::__construct();
        }

        public function setClicksDiseaseId($diseaseId){
            
            try {
                $query = "UPDATE ".TABLE_ARTICLE."SET clicks = clicks + 1 where did = ".$diseaseId;          

                $result = $this->connector->rawQuery($query);
                $this->connector->disconnect();
            }
            catch(Exception $e){
                echo "Couldn't update... please try again later";
                die();
            }
        }

        public function ratingArticle($id,$value,$id_client_site){

            try {
                
                $query = "INSERT INTO ".TABLE_ARTICLES_RATING."
                (
                `article_id`,
                `client_id`,
                `rating`,
                `created_at`)
                VALUES
                (
                '$id',
                '$id_client_site',
                '$value',
                NOW());";

                $result = $this->connector->rawQuery($query);
                $this->connector->disconnect();

                return $result;
            }
            catch(Exception $e){
                $this->connector->disconnect();
                echo "Couldn't rating... please try again later";
                die();
            }

        }
        
    }


?>
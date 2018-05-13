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

        public function ratingArticleSave($id,$value,$id_client_site){

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
                echo "Couldn't save rating... please try again later";
                die();
            }

        }

        public function ratingArticleUpdate($id,$value,$id_client_site){
            try {

                $query = "UPDATE ".TABLE_ARTICLES_RATING."
                SET
                `rating` = $value
                WHERE `article_id` = $id and `client_id`= $id_client_site;
                ";

                $result = $this->connector->rawQuery($query);
                $this->connector->disconnect();

                return $result;
            }
            catch(Exception $e){
                $this->connector->disconnect();
                echo "Couldn't update rating... please try again later";
                die();
            }
            
        }
        public function ratingArticleGet($id,$id_client_site){
            try {

                $query = "SELECT *
                FROM ".TABLE_ARTICLES_RATING." where `article_id` = $id and `client_id` = $id_client_site;";

                $result = $this->connector->rawQuery($query);
                
                $data = array();
                while ($row = $result->fetch_assoc()) {
                    $data[] = $row;
                }

                $this->connector->disconnect();

                return $data;
            }
            catch(Exception $e){
                $this->connector->disconnect();
                echo "Couldn't get rating... please try again later";
                die();
            }
            
        }
        public function commentArticleSave($id,$value,$id_client_site){

            try {
                
                $query = "INSERT INTO ".TABLE_ARTICLES_COMMENT."
                (
                `article_id`,
                `client_id`,
                `comment`,
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
                echo "Couldn't save comment... please try again later";
                die();
            }

        }

        public function commentArticleUpdate($id,$value,$id_client_site){
            try {

                $query = "UPDATE ".TABLE_ARTICLES_COMMENT."
                SET
                `comment` = $value
                WHERE `article_id` = $id and `client_id`= $id_client_site;
                ";

                $result = $this->connector->rawQuery($query);
                $this->connector->disconnect();

                return $result;
            }
            catch(Exception $e){
                $this->connector->disconnect();
                echo "Couldn't update comment... please try again later";
                die();
            }
            
        }
        public function commentArticleGet($id,$id_client_site){
            try {

                $query = "SELECT *
                FROM ".TABLE_ARTICLES_COMMENT." where `article_id` = $id and `client_id` = $id_client_site 
                order by `created_at` desc";

                $result = $this->connector->rawQuery($query);
                
                $data = array();
                while ($row = $result->fetch_assoc()) {
                    $data[] = $row;
                }

                $this->connector->disconnect();

                return $data;
            }
            catch(Exception $e){
                $this->connector->disconnect();
                echo "Couldn't get comment... please try again later";
                die();
            }
            
        }


        #####################################################################################
	    #DISEASES
        public function ratingDiseaseSave($id,$value,$id_client_site){

            try {
                
                $query = "INSERT INTO ".TABLE_DISEASES_RATING."
                (
                `disease_id`,
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
                echo "Couldn't save rating... please try again later";
                die();
            }

        }

        public function ratingDiseaseUpdate($id,$value,$id_client_site){
            try {

                $query = "UPDATE ".TABLE_DISEASES_RATING."
                SET
                `rating` = $value
                WHERE `disease_id` = $id and `client_id`= $id_client_site;
                ";

                $result = $this->connector->rawQuery($query);
                $this->connector->disconnect();

                return $result;
            }
            catch(Exception $e){
                $this->connector->disconnect();
                echo "Couldn't update rating... please try again later";
                die();
            }
            
        }
        public function ratingDiseaseGet($id,$id_client_site){
            try {

                $query = "SELECT *
                FROM ".TABLE_DISEASES_RATING." where `disease_id` = $id and `client_id` = $id_client_site;";

                $result = $this->connector->rawQuery($query);
                
                $data = array();
                while ($row = $result->fetch_assoc()) {
                    $data[] = $row;
                }

                $this->connector->disconnect();

                return $data;
            }
            catch(Exception $e){
                $this->connector->disconnect();
                echo "Couldn't get rating... please try again later";
                die();
            }
            
        }
        public function commentDiseaseSave($id,$value,$id_client_site){

            try {
                
                $query = "INSERT INTO ".TABLE_DISEASES_COMMENT."
                (
                `disease_id`,
                `client_id`,
                `comment`,
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
                echo "Couldn't save comment... please try again later";
                die();
            }

        }

        public function commentDiseaseUpdate($id,$value,$id_client_site){
            try {

                $query = "UPDATE ".TABLE_DISEASES_COMMENT."
                SET
                `comment` = $value
                WHERE `disease_id` = $id and `client_id`= $id_client_site;
                ";

                $result = $this->connector->rawQuery($query);
                $this->connector->disconnect();

                return $result;
            }
            catch(Exception $e){
                $this->connector->disconnect();
                echo "Couldn't update comment... please try again later";
                die();
            }
            
        }
        public function commentDiseaseGet($id,$id_client_site){
            try {

                $query = "SELECT *
                FROM ".TABLE_DISEASES_COMMENT." where `disease_id` = $id and `client_id` = $id_client_site 
                order by `created_at` desc";

                $result = $this->connector->rawQuery($query);
                
                $data = array();
                while ($row = $result->fetch_assoc()) {
                    $data[] = $row;
                }

                $this->connector->disconnect();

                return $data;
            }
            catch(Exception $e){
                $this->connector->disconnect();
                echo "Couldn't get comment... please try again later";
                die();
            }
            
        }




        ###################################################################################
	    #MER TERMS


        public function ratingDiseaseArticleLike($article_id, $term,$pos_start) {


            try {

                $query = "UPDATE ".TABLE_MER_ARTICLES."
                SET
                likes = likes + 1 " . "
                WHERE term = '" . $term .  "' and article_id = " .  $article_id . " and pos_start = " .  $pos_start ;


                $result = $this->connector->rawQuery($query);
                $this->connector->disconnect();

                return $result;


            }

             catch(Exception $e){

                $this->connector->disconnect();
                echo "Couldn't save rating... please try again later";
                die();
            }

        }



    public function ratingDiseaseArticleDislike($article_id, $term,$pos_start) {


            try {

                $query = "UPDATE ".TABLE_MER_ARTICLES."
                SET
                dislikes = dislikes + 1 " . "
                WHERE term = '" . $term .  "' and article_id = " .  $article_id . " and pos_start = " .  $pos_start ;


                $result = $this->connector->rawQuery($query);
                $this->connector->disconnect();

                return $result;


            }

             catch(Exception $e){

                $this->connector->disconnect();
                echo "Couldn't save rating... please try again later";
                die();
            }

        }

    }



?>
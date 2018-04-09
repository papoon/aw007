<?php
    require_once 'model.php';
    class feedback extends Model{

        public function __construct(){
            parent::__construct();
        }

        public function __call($diseaseId){
            
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
        
    }


?>
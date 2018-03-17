<?php
    require_once 'model.php';
    class Disease extends Model{

        public function __construct(){
            parent::__construct();
        }

        public function getDiseases(){

            $data = $this->connector->selectAll(TABLE_DISEASE);
            return $data;
            
        }
        public function getDisease($id){
            $id = mysqli_real_escape_string($this->connector->connect(),$id);
            $data = $this->connector->selectWhere(TABLE_DISEASE,'id','=',$id,'int')->fetch_assoc();
            return $data;
            
        }

    }


?>
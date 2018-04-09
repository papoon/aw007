<?php    
    require_once 'model.php';
    class Photos extends Model{

        public function __construct(){
            parent::__construct();
        }

        public function getPhotosDisease($idDisease){

            //$result = $this->connector->rawQuery('select * from Article
            //where did = '.$idDisease.';');

            //$this->connector->connect();

            $idDisease = mysqli_real_escape_string($this->connector->connect(),$idDisease);
            $result = $this->connector->selectWhere(TABLE_PHOTOS,'did','=',$idDisease,'int');

            $data = array();
            //$this->connector->disconnect();
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }

            $this->connector->disconnect();

            return $data;
        }
    }
?>
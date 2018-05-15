<?php
    require_once 'model.php';
    class Photos extends Model{

        public function __construct(){
            parent::__construct();
        }

        public function getPhotos(){

            $result = $this->connector->selectAll(TABLE_PHOTOS);

            $data = array();
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }

            $this->connector->disconnect();
            return $data;

        }

        public function getPhotosDisease($idDisease){

            //$result = $this->connector->rawQuery('select * from Article
            //where did = '.$idDisease.';');

            //$this->connector->connect();

            $idDisease = mysqli_real_escape_string($this->connector->connect(),$idDisease);

            $query = "SELECT * FROM " . TABLE_PHOTOS . " P WHERE P.did = ".$idDisease." AND P.id NOT IN (SELECT photo_id FROM " . TABLE_PHOTOS_HIDE . " );";

            $result = $this->connector->rawQuery($query);

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

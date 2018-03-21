<?php    
    require_once 'model.php';
    class Tweets extends Model{

        public function __construct(){
            parent::__construct();
        }

        public function getTweetsDisease($idDisease){

            //$result = $this->connector->rawQuery('select * from Article
            //where did = '.$idDisease.';');

            //$this->connector->connect();

            $idDisease = mysqli_real_escape_string($this->connector->connect(),$idDisease);
            $result = $this->connector->selectWhere(TABLE_TWEETS,'did','=',$idDisease,'int');

            //$this->connector->disconnect();
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }

            $this->connector->disconnect();

            return $data;
        }
    }
?>
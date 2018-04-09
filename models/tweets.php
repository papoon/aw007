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
            $data = array();
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }

            $this->connector->disconnect();

            return $data;
        }

        public function getTweetsDiseaseRanked($idDisease){

            $idDisease = mysqli_real_escape_string($this->connector->connect(),$idDisease);
            $result = $this->connector->selectWhere(TABLE_TWEETS,'did','=',$idDisease,'int');

            $data = convertDatasetToArray($result);

            $queryRanks = 'SELECT tweet_id, tweet_rank FROM Inv_Index_Tweets WHERE did = '.$idDisease;
            $queryRanks .= ' UNION ';
            $queryRanks .= 'SELECT id, ~0 >> 45 FROM Tweets WHERE did = '.$idDisease;
            $queryRanks .= ' AND id NOT IN (SELECT tweet_id FROM Inv_Index_Tweets WHERE did = '.$idDisease;
            $queryRanks .= ' ) ORDER BY tweet_rank;';

            $ranks = $this->connector->rawQuery($queryRanks);

            $ranks = convertDatasetToArray($ranks);

            $newData = [];

            foreach ($ranks as $infoRank) {
               #var_dump($infoRank);
               foreach ($data as $key => $value) {
                  if ($value['id'] == $infoRank['tweet_id']) {
                     $newData[$key] = $value;
                  }
               }
            }

            $this->connector->disconnect();

            return $newData;
        }
    }
?>

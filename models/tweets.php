<?php
    require_once 'model.php';
    class Tweets extends Model{

        public function __construct(){
            parent::__construct();
        }

        public function getTweets(){

            $result = $this->connector->selectAll(TABLE_TWEETS);

            $data = array();
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }

            $this->connector->disconnect();
            return $this->utf8magic($data);

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

            return $this->utf8magic($data);
        }

        public function getTweetsDiseaseRanked($idDisease){

            $idDisease = mysqli_real_escape_string($this->connector->connect(),$idDisease);

            $queryRanks  = 'SELECT '.TABLE_TWEETS.'.* FROM '.TABLE_TWEETS.' INNER JOIN (';
            $queryRanks .= 'SELECT tweet_id, tweet_rank FROM Inv_Index_Tweets WHERE did = '.$idDisease;
            $queryRanks .= ' UNION ';
            $queryRanks .= 'SELECT id, ~0 >> 45 FROM Tweets WHERE did = '.$idDisease;
            $queryRanks .= ' AND id NOT IN (SELECT tweet_id FROM Inv_Index_Tweets WHERE did = '.$idDisease.')';
            $queryRanks .= ') AS R ON Tweets.id = R.tweet_id ORDER BY R.tweet_rank, Tweets.id;';


            $ranks = $this->connector->rawQuery($queryRanks);

            $data = convertDatasetToArray($ranks);

            $this->connector->disconnect();

            return $this->utf8magic($data);
        }
    }
?>

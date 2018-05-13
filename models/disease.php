<?php
    require_once 'model.php';
    class Disease extends Model{

        public function __construct(){
            parent::__construct();
        }

        public function getDiseases(){

            $result = $this->connector->selectAll(TABLE_DISEASE);

            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }

            $this->connector->disconnect();
            return $data;

        }
        public function getDisease($id){


            $id = mysqli_real_escape_string($this->connector->connect(),$id);
            $data = $this->connector->selectWhere(TABLE_DISEASE,'id','=',$id,'int')->fetch_assoc();

            $this->connector->disconnect();

            return $data;

        }

        public function getDiseaseMetadata($id){

            $disease = $this->getDisease($id);


            $article = new Article();
            $articles = $article->getArticlesDisease($id);

            $photos = new Photos();
            $photos = $photos->getPhotosDisease($id);

            $tweets = new Tweets();
            $tweets = $tweets->getTweetsDisease($id);


            #array_push($disease,$articles);
            #array_push($disease,$photos);
            #array_push($disease,$tweets);

            $disease['articles'] = $articles;
            $disease['photos'] = $photos;
            $disease['tweets'] = $tweets;

            return $disease;
        }



        //this is used because we are not saving Diseases ID on runing the mer terms finding
        public function getDiseaseID ($name) {

            $data = $this->connector->selectWhere(TABLE_DISEASE,'name','=',$id,'int')->fetch_assoc();

            $this->connector->disconnect();

            return $data;



        }

        public function getSimilarDisease($id) {

            $query = "SELECT disease_id, resnik_value FROM " . TABLE_SIM_DISEASES . " WHERE did = ". $id . " and resnik_value > 0 ORDER BY resnik_value DESC;";

            $result = $this->connector->rawQuery($query);


            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }

            $this->connector->disconnect();

            return $result;


        }



    }


?>

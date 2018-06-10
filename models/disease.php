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

            return $this->utf8magic($data);
        }

        public function getDisease($id){
            $id = mysqli_real_escape_string($this->connector->connect(),$id);
            $data = $this->connector->selectWhere(TABLE_DISEASE,'id','=',$id,'int')->fetch_assoc();

            $this->connector->disconnect();

            return $this->utf8magic($data);
        }

        public function getDiseaseMetadata($id){

            $disease = $this->getDisease($id);

            $article = new Article();
            $articles = $article->getArticlesDiseaseRanked($id);

            $photos = new Photos();
            $photos = $photos->getPhotosDisease($id);

            $tweets = new Tweets();
            $tweets = $tweets->getTweetsDiseaseRanked($id);

            $similarDiseases = $this->getSimilarDisease($id);


            #array_push($disease,$articles);
            #array_push($disease,$photos);
            #array_push($disease,$tweets);

            $disease['articles'] = $articles;
            $disease['photos'] = $photos;
            $disease['tweets'] = $tweets;
            $disease['similarDiseases'] = $similarDiseases;

            return $this->utf8magic($disease);
        }



        //this is used because we are not saving Diseases ID on runing the mer terms finding
        public function getDiseaseID ($name) {
            $data = $this->connector->selectWhere(TABLE_DISEASE,'name','=',$id,'int')->fetch_assoc();

            $this->connector->disconnect();

            return $data;
        }

        public function getSimilarDisease($id) {
            $this->connector->connect();
            $query = "SELECT disease_id, name, resnik_value FROM " . TABLE_SIM_DISEASES . " INNER JOIN " . TABLE_DISEASE . " ON " . TABLE_DISEASE . ".id = " . TABLE_SIM_DISEASES . ".disease_id WHERE did = ". $id . " and resnik_value > 0 ORDER BY resnik_value DESC;";

            $result = $this->connector->rawQuery($query);

            $data = array();
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }

            return $this->utf8magic($data);
        }
    }

?>

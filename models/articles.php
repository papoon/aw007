<?php
    require_once 'model.php';
    class Article extends Model{



        public function __construct(){
            parent::__construct();



        }
        public function getArticles(){

            $result = $this->connector->selectAll(TABLE_ARTICLE);

            $data = array();
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }

            $this->connector->disconnect();
            return $this->utf8magic($data);

        }
        public function getArticle($id){
            $id = mysqli_real_escape_string($this->connector->connect(),$id);
            $data = $this->connector->selectWhere(TABLE_ARTICLE,'id','=',$id,'int')->fetch_assoc();

            $this->connector->disconnect();

            return $this->utf8magic($data);

        }

        public function getArticlesDisease($idDisease){

            $idDisease = mysqli_real_escape_string($this->connector->connect(),$idDisease);
            $result = $this->connector->selectWhere(TABLE_ARTICLE,'did','=',$idDisease,'int');

            $data = array();
            //$this->connector->disconnect();
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }

            $this->connector->disconnect();

            return $this->utf8magic($data);
        }

        public function getArticlesDiseaseRanked($idDisease){
            $idDisease = mysqli_real_escape_string($this->connector->connect(),$idDisease);

            $queryRanks  = 'SELECT '.TABLE_ARTICLE.'.* FROM '.TABLE_ARTICLE.' INNER JOIN (';
            $queryRanks .= 'SELECT article_id, article_rank FROM Inv_Index_Articles WHERE did = '.$idDisease;
            $queryRanks .= ' UNION ';
            $queryRanks .= 'SELECT id, ~0 >> 45 FROM Article WHERE did = '.$idDisease;
            $queryRanks .= ' AND id NOT IN (SELECT article_id FROM Inv_Index_Articles WHERE did = '.$idDisease.')';
            $queryRanks .= ') AS R ON Article.id = R.article_id ORDER BY R.article_rank, Article.id;';

            $ranks = $this->connector->rawQuery($queryRanks);

            $data = convertDatasetToArray($ranks);

            $this->connector->disconnect();

            return $this->utf8magic($data);
        }

        public function getMERTerms($id){
            $id = mysqli_real_escape_string($this->connector->connect(),$id);
            $data = $this->connector->selectWhere(TABLE_MER_ARTICLES,'article_id','=',$id,'int');

            $data = convertDatasetToArray($data);

            $this->connector->disconnect();

            return $this->utf8magic($data);

        }

        public function getRelatedArticlesDisease($id_disease,$top=0){

            try{

                $query = "select * from ".TABLE_ARTICLE." 
                where did = $id_disease order by relevance desc limit $top";

            
                $result = $this->connector->rawQuery($query);

                $data = array();
                while ($row = $result->fetch_assoc()) {
                    $data[] = $row;
                }

                $this->connector->disconnect();

                return $data;

            }catch(Exception $e){
                $this->connector->disconnect();
                echo "Couldn't get top-n related articles for disease... please try again later";
                die();
            }

            

        }




    }




?>

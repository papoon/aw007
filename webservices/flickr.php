<?php

    include_once "../utils/request.php";
    include_once "../private/private.php";
    
    class Flickr{

        private $uri = "https://api.flickr.com/services/rest/";
        private $API_KEY;
        private $method = "flickr.photos.search";
        private $queryText;
        private $numberPhotos;
        private $privacyFilter=1;
        private $safeSearch=1;

        function __construct($queryText,$numberPhotos=10)
        {   
            $this->API_KEY = $GLOBALS['FLICKR_API_KEY'];
            $this->queryText = $queryText;
            $this->numberPhotos = $numberPhotos;
            $this->obj = $this->getResponse();
        }

        private function getUrlXML(){

            $searchUrl = $this->uri.'?method='.$this->method.'&api_key='.$this->API_KEY.'&text='.$this->queryText.'&per_page='.$this->numberPhotos.'&privacy_filter='.$this->privacyFilter.'&safe_search='.$this->safeSearch;
            return $searchUrl;
        }

        private function getResponseXML($url){

            $response =  getResponseCurl($url);

            $xml = simplexml_load_string($response,"SimpleXMLElement",LIBXML_NOCDATA);

            $json = json_encode($xml);

            $response = json_decode($json,TRUE);

            return $response;
        }

        public function getResponse(){

            $response = $this->getResponseXML($this->getUrlXML());
            return $response;
        }

        public function getStatus(){
            return $this->obj['attributes']['stat'];
        }
        public function getPhotos(){
            return $this->obj['photos']['photo'];
        }
        public function getPhotosUrl(){

            $photoUri = "https://farm{farm-id}.staticflickr.com/{server-id}/{id}_{secret}.jpg";
            $photos = $this->getPhotos();
            $photosUrl = array();

            foreach($photos as $key=>$photo){
                $photoId = $photo['@attributes']['id'];
                $farmId = $photo['@attributes']['farm'];
                $serverId = $photo['@attributes']['server'];
                $secret = $photo['@attributes']['secret'];

                $photosUrl["$photoId"][] = 'https://farm'.$farmId.'.staticflickr.com/'.$serverId.'/'.$photoId.'_'.$secret.'.jpg';
                
            }

            return $photosUrl;
            

        }







    }


?>
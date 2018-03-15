<?php

    include_once "../utils/request.php";
    include_once "../utils/helpers.php";
    include_once "../private/private.php";

    class Flickr{

        private $uri = "https://api.flickr.com/services/rest/";
        private $API_KEY = FLICKR_API_KEY;
        private $method = "flickr.photos.search";
        private $queryText;
        private $numberPhotos;
        private $privacyFilter=1;
        private $safeSearch=1;

        function __construct($queryText,$numberPhotos=10)
        {
            $this->queryText = getStringForUrl($queryText);
            $this->numberPhotos = $numberPhotos;
            $this->obj = $this->getResponse();
        }

        private function getUrlXML(){

            $searchUrl = $this->uri;
            $searchUrl .= '?method='.$this->method;
            $searchUrl .= '&api_key='.$this->API_KEY;
            $searchUrl .= '&text='.$this->queryText;
            $searchUrl .= '&per_page='.$this->numberPhotos;
            $searchUrl .= '&privacy_filter='.$this->privacyFilter;
            $searchUrl .= '&safe_search='.$this->safeSearch;
            //only get photos uploaded until 7 days ago
            //$searchUrl .= '&min_upload_date='.getOneYearAgoTimestamp();
            //echo $searchUrl.PHP_EOL;
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
            //flickr request is bringing 100 photos ever with per_page set...
            $photos = array_slice($photos, 0, $this->numberPhotos);
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

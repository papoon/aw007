<?php

    include_once "../utils/request.php";
    include_once "../utils/helpers.php";
    include_once "../private/private.php";

    class FlickrPhotoInfo{

        private $uri = "https://api.flickr.com/services/rest/";
        private $API_KEY = FLICKR_API_KEY;
        private $method = "flickr.photos.getInfo";
        private $photoId;
        private $secret;
        private $photo;


        function __construct($photoId)
        {

            $this->photoId = $photoId;
            $response = $this->getResponse();
            $this->photo = $response;
        }
        private function getUrlXML(){

            $searchUrl = $this->uri;
            $searchUrl .= '?method='.$this->method;
            $searchUrl .= '&api_key='.$this->API_KEY;
            $searchUrl .= '&photo_id='.$this->photoId;
            //$searchUrl .= '&secret='.$this->secret;

            return $searchUrl;
        }

        private function getResponseXML($url){

            $response =  getResponseCurl($url);

            $xml = simplexml_load_string($response,"SimpleXMLElement",LIBXML_NOCDATA);

            $json = json_encode($xml);

            $response = json_decode($json,TRUE);

            return $response;
        }

        private function getResponse(){

            $response = $this->getResponseXML($this->getUrlXML());
            return $response;
        }

        public function isValidPhoto() {

            //if the photo is not valid, the 'photo' array is not present;
            //an 'err' array is present instead
            return (array_key_exists('photo',$this->photo));
        }

        public function getPhotoAuthorName(){
            return  $this->photo['photo']['owner']['@attributes']['realname'];
        }
        public function getPhotoUserName(){
            return $this->photo['photo']['owner']['@attributes']['username'];
        }
        public function getNumberOfLikes(){
            return $this->photo['photo']['@attributes']['isfavorite'];
        }
        public function getPhotoNumberOfComments(){
            return $this->photo['photo']['comments'];
        }
        public function getPhotoNumberOfViews(){
            return $this->photo['photo']['@attributes']['views'];
        }
        public function getUserLocation(){
            return $this->photo['photo']['owner']['@attributes']['location'];
        }
        public function getPhotoPublishedAt(){
            return $this->photo['photo']['dates']['@attributes']['taken'];
        }
    }

?>

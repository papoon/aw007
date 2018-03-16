<?php

    include_once "../utils/request.php";
    include_once "../utils/helpers.php";
    include_once "../private/private.php";
    include_once "../webservices/flickrPhotoInfo.php";

    class Flickr{

        private $uri = "https://api.flickr.com/services/rest/";
        private $API_KEY = FLICKR_API_KEY;
        private $method = "flickr.photos.search";
        private $queryText;
        private $numberPhotos;
        private $privacyFilter=1;
        private $safeSearch=1;
        private $photoId;
        private $secret;
        private $photo;


        function __construct($queryText,$numberPhotos=10)
        {
            $this->queryText = getStringForUrl($queryText);
            $this->numberPhotos = $numberPhotos;
            $this->obj = $this->getResponse();
        }

        private function getUrlXML(){

            if($this->method == "flickr.photos.search"){
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
            }

            if($this->method == "flickr.photos.getInfo"){
                $searchUrl = $this->uri;
                $searchUrl .= '?method='.$this->method;
                $searchUrl .= '&api_key='.$this->API_KEY;
                $searchUrl .= '&photo_id='.$this->photoId;
                //$searchUrl .= '&secret='.$this->secret;

            }

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
        public function photos(){
            return $this->obj['photos']['photo'];
        }
        public function getPhotos(){

            $photoUri = "https://farm{farm-id}.staticflickr.com/{server-id}/{id}_{secret}.jpg";
            $photos = $this->photos();
            //flickr request is bringing 100 photos ever with per_page set...
            $photos = array_slice($photos, 0, $this->numberPhotos);
            $photosData = array();

            if($this->numberPhotos > 1){
                foreach($photos as $key=>$photo){
                    $photoId = $photo['@attributes']['id'];
                    $farmId = $photo['@attributes']['farm'];
                    $serverId = $photo['@attributes']['server'];
                    $secret = $photo['@attributes']['secret'];
    
                    $photosData["$photoId"]['url'] = 'https://farm'.$farmId.'.staticflickr.com/'.$serverId.'/'.$photoId.'_'.$secret.'.jpg';
                    $photosData["$photoId"]['serverId'] = $serverId;
                    $photosData["$photoId"]['secret'] = $secret;
                    $photosData["$photoId"]['farmId'] = $farmId;

                    $flickrPhotoInfo = new FlickrPhotoInfo($photoId);

                    $photosData["$photoId"]['authorName'] = $flickrPhotoInfo->getPhotoAuthorName();
                    $photosData["$photoId"]['username'] = $flickrPhotoInfo->getPhotoUserName();
                    $photosData["$photoId"]['numerOfLikes'] = $flickrPhotoInfo->getNumberOfLikes();
                    $photosData["$photoId"]['numberOfComments'] = $flickrPhotoInfo->getPhotoNumberOfComments();
                    $photosData["$photoId"]['views'] = $flickrPhotoInfo->getPhotoNumberOfViews();
                    $photosData["$photoId"]['location'] = $flickrPhotoInfo->getUserLocation();
                    $photosData["$photoId"]['publishedAt'] = $flickrPhotoInfo->getPhotoPublishedAt();

                }
            }
            else{

                $photoId = $photos['@attributes']['id'];
                $farmId = $photos['@attributes']['farm'];
                $serverId = $photos['@attributes']['server'];
                $secret = $photos['@attributes']['secret'];
    
                $photosData["$photoId"]['url'] = 'https://farm'.$farmId.'.staticflickr.com/'.$serverId.'/'.$photoId.'_'.$secret.'.jpg';
                $photosData["$photoId"]['serverId'] = $serverId;
                $photosData["$photoId"]['secret'] = $secret;
                $photosData["$photoId"]['farmId'] = $farmId;

                $flickrPhotoInfo = new FlickrPhotoInfo($photoId);

                $photosData["$photoId"]['authorName'] = $flickrPhotoInfo->getPhotoAuthorName();
                $photosData["$photoId"]['username'] = $flickrPhotoInfo->getPhotoUserName();
                $photosData["$photoId"]['numerOfLikes'] = $flickrPhotoInfo->getNumberOfLikes();
                $photosData["$photoId"]['numberOfComments'] = $flickrPhotoInfo->getPhotoNumberOfComments();
                $photosData["$photoId"]['views'] = $flickrPhotoInfo->getPhotoNumberOfViews();
                $photosData["$photoId"]['loacation'] = $flickrPhotoInfo->getUserLocation();
                $photosData["$photoId"]['publishedAt'] = $flickrPhotoInfo->getPhotoPublishedAt();
            }

            
            return $photosData;
        }
        public function getPhotoInfo($photoId){

            $this->method = "flickr.photos.getInfo";
            $this->photoId = $photoId;
            $response = $this->getResponse();
            $this->photo = $response;
            //return $response;
            
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

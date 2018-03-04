<?php
    include_once 'twitterStatus.php';
    include_once "../utils/request.php";
    class TwitterEmbed{
        private $uri = "https://publish.twitter.com/oembed";
        private $tweetId;

        function __construct($tweetId)
        {   
            $this->tweetId = $tweetId;
            //$this->obj = $this->getResponse();
            
        }
        private function getUrl(){

            $twitterStatus = new TwitterStatus($this->tweetId);
            $url = $twitterStatus->getTwitterUrl();

            $searchUrl = $this->uri.'?url='.$url;
            return $searchUrl;
        }

        public function getResponse(){

            $json =  getResponseCurl($this->getUrl());

            $response = json_decode($json,TRUE);

            return $response;
        }
    }


?>
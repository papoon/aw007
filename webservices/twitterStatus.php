<?php
    include_once "../utils/request.php";
    class TwitterStatus{
        private $uri = "https://twitter.com/statuses/";
        private $tweetId;

        function __construct($tweetId)
        {   
            $this->tweetId = $tweetId;
            //$this->obj = $this->getResponse();
           
        }
        private function getUrl(){

            $searchUrl = $this->uri.$this->tweetId;
            return $searchUrl;
        }
        public function getTwitterUrl(){

            $response =  getResponseCurl($this->getUrl());

            $dom = new DOMDocument;
            $dom->loadHTML($response);

            $node = $dom->getElementsByTagName('a')->item(0);

            $href = $node->getAttribute( 'href' );

            //$response = json_decode($json,TRUE);

            return $href;
        }


    }

?>
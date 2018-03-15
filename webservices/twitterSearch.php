<?php
    include_once '../utils/helpers.php';
    include_once '../utils/request.php';
    include_once "../private/private.php";
    class TwitterSearch{

        private $uri = "https://api.twitter.com/1.1/search/tweets.json";
        private $API_OAUTH_ACCESS_TOKEN = API_OAUTH_ACCESS_TOKEN;
        private $API_OAUTH_ACCESS_TOKEN_SECRET = API_OAUTH_ACCESS_TOKEN_SECRET;
        private $API_CONSUMER_KEY = API_CONSUMER_KEY;
        private $API_CONSUMER_SECRET = API_CONSUMER_SECRET;
        private $query;
        private $count;
        private $result_type = "mixed";
        private $max_id;
        private $since_id;

        //$query = array( 'count' => 100, 'q' => urlencode($search), "result_type" => "recent");

        function __construct($query,$count=10,$max_id="")
        {   
            $this->query = urlencode($query);
            $this->count = $count;
            //$this->max_id = $max_id;
            $this->obj = $this->getResponse();
        }

        private function getUrlSearch(){

            $query = array( 'count' => $this->count, 'q' => $this->query, "result_type" => $this->result_type, "include_entities" => true);
            if($this->max_id != ""){
                $query['max_id'] = $this->max_id;
            }
            if($this->since_id != ""){
                $query['since_id'] = $this->since_id;
            }

            $oauth = array(
                'oauth_consumer_key' => $this->API_CONSUMER_KEY,
                'oauth_nonce' => time(),
                'oauth_signature_method' => 'HMAC-SHA1',
                'oauth_token' => $this->API_OAUTH_ACCESS_TOKEN,
                'oauth_timestamp' => time(),
                'oauth_version' => '1.0'
            );

            $base_params = empty($query) ? $oauth : array_merge($query,$oauth);
            $base_info = buildBaseString($this->uri, 'GET', $base_params);
            $url = empty($query) ? $this->uri : $this->uri . "?" . http_build_query($query);

            $composite_key = rawurlencode($this->API_CONSUMER_SECRET) . '&' . rawurlencode($this->API_OAUTH_ACCESS_TOKEN_SECRET);
            $oauth_signature = base64_encode(hash_hmac('sha1', $base_info, $composite_key, true));
            $oauth['oauth_signature'] = $oauth_signature;

            $header = array(buildAuthorizationHeader($oauth), 'Expect:');

            $response = getResponseCurl($url,$header);

            return $response;

            //$searchUrl = $this->uri.'&id='.$this->id.'&retmode='.$this->retmode.'&rettype='.$this->rettype;
            //return $searchUrl;
        }
        private function getResponse(){

            $response = $this->getUrlSearch();
            return json_decode($response,true);
        }

        public function getTweets(){

            $tweets = $this->obj['statuses'];
            
            return $tweets;
        }

        public function getTweetId($tweet){
            return $tweet['id_str'];
        }
        public function searchTweetId($id){
            
            //$id_since = $id - 1;
            //$this->query .= '&max_id='.$id;
            $this->max_id = $id;
            $this->count = 1;
            $response = $this->getResponse();

            return $response['statuses'][0];
        }
        public function getAuthorName($tweet){
            return $tweet['user']['name'];
        }
        public function getUsername($tweet){
            return $tweet['user']['screen_name'];
        }
        public function getNumberOfLikes($tweet){
            return $tweet['favorite_count'];
        }
        public function getNumberOfComments($tweet){
            return null;
        }
        public function getNumberOfShares($tweet){
            return $tweet['retweet_count'];
        }
        public function getAuthorLocation($tweet){
            return $tweet['user']['location'];
        }
        public function getPublishedDate($tweet){
            $date = new DateTime($tweet['created_at']);
            return $date->format('Y-m-d');
            
        }




    }

?>
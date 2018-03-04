<?php
    include_once '../utils/helpers.php';
    include_once '../utils/request.php';
    include_once "../private/private.php";
    class TwitterSearch{

        private $uri = "https://api.twitter.com/1.1/search/tweets.json";
        private $API_OAUTH_ACCESS_TOKEN;
        private $API_OAUTH_ACCESS_TOKEN_SECRET;
        private $API_CONSUMER_KEY;
        private $API_CONSUMER_SECRET;
        private $query;
        private $count;
        private $result_type = "recent";

        //$query = array( 'count' => 100, 'q' => urlencode($search), "result_type" => "recent");

        function __construct($query,$count=10)
        {   
            $this->query = $query;
            $this->count = $count;
            $this->API_OAUTH_ACCESS_TOKEN = $GLOBALS['API_OAUTH_ACCESS_TOKEN'];
            $this->API_OAUTH_ACCESS_TOKEN_SECRET = $GLOBALS['API_OAUTH_ACCESS_TOKEN_SECRET'];
            $this->API_CONSUMER_KEY = $GLOBALS['API_CONSUMER_KEY'];
            $this->API_CONSUMER_SECRET = $GLOBALS['API_CONSUMER_SECRET'];

            //$this->obj = $this->getResponse();
        }

        private function getUrlSearch(){

            $query = array( 'count' => $this->count, 'q' => urlencode($this->query), "result_type" => $this->result_type);

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
        public function getResponse(){

            $response = $this->getUrlSearch();
            return json_decode($response,true);
        }
    }

?>
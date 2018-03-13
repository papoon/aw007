<?php
    include_once "../utils/request.php";
    class PubMedSearch{

        private $uri = "https://eutils.ncbi.nlm.nih.gov/entrez/eutils/esearch.fcgi?db=pubmed";
        private $retmode = "xml";
        private $retmax;
        private $term;

        function __construct($term,$retmax = 10)
        {
            $this->term = $term;
            $this->retmax = $retmax;
        }

        private function getUrlSearchXML(){

            $searchUrl = $this->uri.'&term='.urlencode($this->term).'&retmax='.$this->retmax.'&retmode='.$this->retmode;

            return $searchUrl;

        }

        private function getResponseXML($url){

            $response =  getResponseCurl($url);

            $xml = simplexml_load_string($response,"SimpleXMLElement",LIBXML_NOCDATA);

            $json = json_encode($xml);

            $response = json_decode($json,TRUE);

            return $response;
        }


        public function getIdList(){

            $response = $this->getResponseXML($this->getUrlSearchXML());
            return $response['IdList'];
        }
    }


?>

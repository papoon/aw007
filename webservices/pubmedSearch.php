<?php
    include_once "../utils/request.php";
    class PubMedSearch{

        private $uri = "https://eutils.ncbi.nlm.nih.gov/entrez/eutils/esearch.fcgi?db=pubmed";
        private $retmode = "xml";
        private $retmax;
        private $term;
        private $startDate;

        function __construct($term,$retmax = 10)
        {
            $this->term = $term;
            $this->retmax = $retmax;
            //$this->startDate = $startAt;
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

        public function getIdLists(){
            
            $response = $this->getResponseXML($this->getUrlSearchXML());
            if(is_array($response['IdList']['Id'])){
                return $response['IdList'];
            }
            else{
                return array("Id"=>array($response['IdList']['Id']));
            }
            
        }
        public function getResponseDebug(){
            $response = $this->getResponseXML($this->getUrlSearchXML());
            return $response;
        }
        public function getIdListsByDate($startAt){
            
            //$query = '+AND+("2018/01/03":"3000"[Date%20-%20Entrez])';
            $query = '+ AND +("'.$startAt.'":"3000"[Date - Entrez])';

            $this->term .= $query;

            $response = $this->getResponseXML($this->getUrlSearchXML());
            return $response;


        }
    }


?>

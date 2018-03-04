<?php

    include_once "../utils/request.php";
    class PubMedFech{
        
        private $uri = "https://eutils.ncbi.nlm.nih.gov/entrez/eutils/efetch.fcgi?db=pubmed";
        private $retmode = "xml";
        private $rettype;
        private $id;

        function __construct($id)
        {   
            $this->id = $id;
            $this->obj = $this->getResponse();
           
        }

        private function getUrlFeachXML(){

            $searchUrl = $this->uri.'&id='.$this->id.'&retmode='.$this->retmode.'&rettype='.$this->rettype;
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

            $response = $this->getResponseXML($this->getUrlFeachXML());
            return $response;
        }

        public function getArticle(){
            return $this->obj['PubmedArticle']['MedlineCitation']['Article'];
        }

        public function getArticleTitle(){

            $article = $this->getArticle();
            return $article['ArticleTitle'];
            
        }
        public function getArticleAbstract(){

            $article = $this->getArticle();
            $abstractText = $article['Abstract']['AbstractText'];

            if(!is_array($abstractText)){
                return $abstractText;
            }
            else{
                $text = "";
                foreach($abstractText as $key=>$value){
                    $text.=$value;
                }

                return $text;
            }
        }
        public function getArticleDate(){

            $article = $this->getArticle();
            $dataArtigo = $article['Journal']['JournalIssue']['PubDate'];

            switch(count($dataArtigo)){
                case 3:
                    return $dataArtigo['Day'].'-'.$dataArtigo['Month'].'-'.$dataArtigo['Year'];
                case 2:
                    return $dataArtigo['Month'].'-'.$dataArtigo['Year'];
                case 1:
                    return $dataArtigo['Year'];
                default:
                    return 'No publish date available';
            }

            //return $dataArtigo['Day'].'-'.$dataArtigo['Month'].'-'.$dataArtigo['Year'];
        }

    }

?>
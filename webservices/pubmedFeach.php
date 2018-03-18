<?php

    include_once "../utils/request.php";
    class PubMedFeach{

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

            if(!array_key_exists('Abstract',$article)){
                throw new Exception('Not exist Abstract!');
            }
            else{

                if(!array_key_exists('AbstractText',$article['Abstract'])){
                    throw new Exception('Not exist Abstract!');
                }
                else{
                    
                    $abstractText = $article['Abstract']['AbstractText'];

                    if(!is_array($abstractText)){
                        return $abstractText;
                    }
                    else{
                        $text = "";
                        foreach($abstractText as $key=>$value){
                            if(!is_array($value)){
                                $text.=$value;
                            }
                            else{
                                throw new Exception('Not expected array of arrays!');
                            }
                        }
                        return $text;
                    }
                }

                
            }
            
        }
        public function getArticleJournalPubDate(){

            $article = $this->getArticle();
            $dataArtigo = $article['Journal']['JournalIssue']['PubDate'];

            #echo '----'.implode($dataArtigo, '|').PHP_EOL;

            switch(count($dataArtigo)){
                case 3:
                    return $dataArtigo['Day'].'-'.$dataArtigo['Month'].'-'.$dataArtigo['Year'];
                case 2:
                    return $dataArtigo['Month'].'-'.$dataArtigo['Year'];
                case 1:
                    if (array_key_exists('Year', $dataArtigo)) {
                      return $dataArtigo['Year'];
                    }
                    else {
                      //dates with $dataArtigo['MedlineDate'] = '2017 Oct-Dec' for example
                      $parts = explode(' ', $dataArtigo['MedlineDate']);
                      return $parts[0].'-01-01';
                    }
                default:
                    return 'No publish date available';
            }

            //return $dataArtigo['Day'].'-'.$dataArtigo['Month'].'-'.$dataArtigo['Year'];
        }
        public function getArticleJournalId(){

            $article = $this->getArticle();
            $journalId = $article['Journal']['ISSN'];

            return $journalId;
        }
        public function getArticleDate($format="d-m-y"){

            $article = $this->getArticle();
            if(array_key_exists('ArticleDate',$article)){
                $articleDate = $article['ArticleDate'];
            }
            else{
                return 'Date not found!';
            }


            /*switch(count($dataArtigo)){
                case 3:
                    return $dataArtigo['Day'].'-'.$dataArtigo['Month'].'-'.$dataArtigo['Year'];
                case 2:
                    return $dataArtigo['Month'].'-'.$dataArtigo['Year'];
                case 1:
                    return $dataArtigo['Year'];
                default:
                    return 'No publish date available';
            }*/
            $format = strtolower($format);
            if($format == "d-m-y"){
                return $articleDate['Day'].'-'.$articleDate['Month'].'-'.$articleDate['Year'];
            }
            elseif($format == "m-d-y"){
                return $articleDate['Month'].'-'.$articleDate['Day'].'-'.$articleDate['Year'];
            }
            elseif($format == "y-m-d"){
                return $articleDate['Month'].'-'.$articleDate['Day'].'-'.$articleDate['Year'];
            }
            else{
                throw new Exception('Date format not available!');
            }


        }
        public function getArticleRevisionDate(){

            $dateRevised = $this->obj['PubmedArticle']['MedlineCitation']['DateRevised'];

            switch(count($dateRevised)){
                case 3:
                    return $dateRevised['Day'].'-'.$dateRevised['Month'].'-'.$dateRevised['Year'];
                case 2:
                    return $dateRevised['Month'].'-'.$dateRevised['Year'];
                case 1:
                    return $dateRevised['Year'];
                default:
                    return 'No revision date available';
            }
        }
        public function getArticleAuthors(){

            $article = $this->getArticle();

            $authors = $article['AuthorList']['Author'];

            $authorsNames = array();

            if(!array_key_exists('ForeName',$authors)){
                foreach($authors as $key=>$author){
                    $lastName = $author['LastName'];
                    $firsName = $author['ForeName'];
                    $initials = $author['Initials'];

                    $authorsNames[] = $lastName.', '.$firsName.', '.$initials;

                }
            }
            else{
                $lastName = $authors['LastName'];
                $firsName = $authors['ForeName'];
                $initials = $authors['Initials'];

                $authorsNames[] = $lastName.', '.$firsName.', '.$initials;
            }


            return $authorsNames;
        }
    }

?>

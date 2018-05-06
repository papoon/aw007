<?php
require_once("simpleRest.php");
require_once("../models/articles.php");
require_once("../models/feedback.php");

class FeedbackRestHandler extends SimpleRest {
    
    //parametros permitidos nesta API 
    private $searchOptions = array('fields','limit');
    
	public function __construct(){
		//$this->setValidVerbs(array('GET'));
		parent::__construct();
	}

	public function encodeHtml($responseData) {

        $htmlResponse = "<table border='1'>";

		foreach($responseData as $key=>$item) {

            if (!empty($item)) {

                $htmlResponse .= "<tr>";

                foreach($item as $value){

                    $htmlResponse .= "<td>". $key. "</td><td>". $value. "</td>";
                }

                $htmlResponse .= "</tr>";
            }
        }

        $htmlResponse .= "</table>";
		return "<html>".$htmlResponse."</html>";
	}

	public function encodeJson($responseData) {

		$jsonResponse = json_encode($responseData);
		return $jsonResponse;
	}

	public function encodeXml($responseData) {
		// creating object of SimpleXMLElement
		$xml = new SimpleXMLElement('<?xml version="1.0"?><feedback></feedback>');
		$this->array_to_xml( $responseData,$xml);
		return $xml->asXML();
	}

	
	//recive GET/POST
	public function rating(){
		
		$this->setValidVerbs(array('GET','POST'));
		$this->errorResponse();

		$request_method = $this->getHttpVerb();
		
		if($request_method == "GET"){

			if(isset($_GET)){

			}
		}

		if($request_method == "POST"){
		
			$article_id = $_POST['article_id'];
			$client_id = $_POST['client_id'];
			$rating = $_POST['rating'];

			$response = $this->ratingArticle($article_id,$rating,$client_id);


			$this->convertResponse($response);

		}
	}
	public function ratingArticle($id,$value,$client_id){

		$feedback = new Feedback();
		$response = $feedback->ratingArticle($id,$value,$client_id);

		return $response;

	}
}
?>
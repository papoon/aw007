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
		
		$this->setValidVerbs(array('GET','POST','PUT'));
		$this->errorResponse();

		$request_method = $this->getHttpVerb();

		if($request_method == "POST"){

			$data = json_decode(file_get_contents("php://input"),true);
		
			$article_id = $data['article_id'];
			$client_id = $data['client_id'];
			$rating = $data['rating'];

			$response = $this->ratingArticleSave($article_id,$rating,$client_id);


			$this->convertResponse($response);

		}
		if($request_method == "PUT"){

			$data = json_decode(file_get_contents("php://input"),true);
			

			$article_id = $data['article_id'];
			$client_id = $data['client_id'];
			$rating = $data['rating'];

			$response = $this->ratingArticleUpdate($article_id,$rating,$client_id);


			$this->convertResponse($response);
		}

		if($request_method == "GET"){
		
			$article_id = $_GET['article_id'];
			$client_id = $_GET['client_id'];

			$response = $this->ratingArticleGet($article_id,$client_id);


			$this->convertResponse($response);

		}


	}
	public function ratingArticleSave($id,$value,$client_id){

		$feedback = new Feedback();
		$response = $feedback->ratingArticleSave($id,$value,$client_id);

		return $response;

	}
	public function ratingArticleUpdate($id,$value,$client_id){

		$feedback = new Feedback();
		$response = $feedback->ratingArticleUpdate($id,$value,$client_id);

		return $response;

	}
	public function ratingArticleGet($id,$client_id){

		$feedback = new Feedback();
		$response = $feedback->ratingArticleGet($id,$client_id);

		return $response;
	}
}
?>
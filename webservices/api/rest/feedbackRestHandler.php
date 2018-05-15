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

    $jsonResponse = json_encode($responseData,JSON_PARTIAL_OUTPUT_ON_ERROR);
    $jsonResponse = $this->prettyPrint($jsonResponse);
    return $jsonResponse;
	}

	public function encodeXml($responseData) {
		// creating object of SimpleXMLElement
		$xml = new SimpleXMLElement('<?xml version="1.0"?><feedback></feedback>');
		$this->array_to_xml( $responseData,$xml);
		return $xml->asXML();
	}


	#####################################################################################
	#ARTICLES
	//recive GET/POST
	public function ratingArticle($id){

		$this->setValidVerbs(array('GET','POST','PUT'));
		$this->errorResponse();

		$request_method = $this->getHttpVerb();

		if($request_method == "POST"){

			$data = json_decode(file_get_contents("php://input"),true);

			$article_id = $id;
			$client_id = $data['client_id'];
			$rating = $data['rating'];

			$response = $this->ratingArticleSave($article_id,$rating,$client_id);


			$this->convertResponse($response);

		}
		if($request_method == "PUT"){

			$data = json_decode(file_get_contents("php://input"),true);


			$article_id = $id;
			$client_id = $data['client_id'];
			$rating = $data['rating'];

			$response = $this->ratingArticleUpdate($article_id,$rating,$client_id);


			$this->convertResponse($response);
		}

		if($request_method == "GET"){

			$article_id = $id;
			$client_id = $_GET['client_id'];

			$response = $this->ratingArticleGet($article_id,$client_id);


			$this->convertResponse($response);

		}


	}
	public function commentArticle($id){

		$this->setValidVerbs(array('GET','POST'));
		$this->errorResponse();

		$request_method = $this->getHttpVerb();

		if($request_method == "POST"){

			$data = json_decode(file_get_contents("php://input"),true);

			$article_id = $id;
			$client_id = $data['client_id'];
			$comment = $data['comment'];

			$response = $this->commentArticleSave($article_id,$comment,$client_id);


			$this->convertResponse($response);

		}
		if($request_method == "PUT"){

			$data = json_decode(file_get_contents("php://input"),true);


			$article_id = $id;
			$client_id = $data['client_id'];
			$comment = $data['comment'];

			$response = $this->commentArticleUpdate($article_id,$comment,$client_id);


			$this->convertResponse($response);
		}

		if($request_method == "GET"){

			$article_id = $id;
			$client_id = $_GET['client_id'];

			$response = $this->commentArticleGet($article_id,$client_id);


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
	public function commentArticleSave($id,$value,$client_id){

		$feedback = new Feedback();
		$response = $feedback->commentArticleSave($id,$value,$client_id);

		return $response;

	}
	public function commentArticleUpdate($id,$value,$client_id){

		$feedback = new Feedback();
		$response = $feedback->commentArticleUpdate($id,$value,$client_id);

		return $response;

	}
	public function commentArticleGet($id,$client_id){

		$feedback = new Feedback();
		$response = $feedback->commentArticleGet($id,$client_id);

		return $response;
	}



	#####################################################################################
	#DISEASES


	public function ratingDisease($id){

		$this->setValidVerbs(array('GET','POST','PUT'));
		$this->errorResponse();

		$request_method = $this->getHttpVerb();

		if($request_method == "POST"){

			$data = json_decode(file_get_contents("php://input"),true);

			$disease_id = $id;
			$client_id = $data['client_id'];
			$rating = $data['rating'];

			$response = $this->ratingDiseaseSave($disease_id,$rating,$client_id);


			$this->convertResponse($response);

		}
		if($request_method == "PUT"){

			$data = json_decode(file_get_contents("php://input"),true);


			$disease_id = $id;
			$client_id = $data['client_id'];
			$rating = $data['rating'];

			$response = $this->ratingDiseaseUpdate($disease_id,$rating,$client_id);


			$this->convertResponse($response);
		}

		if($request_method == "GET"){

			$disease_id = $id;
			$client_id = $_GET['client_id'];

			$response = $this->ratingDiseaseGet($disease_id,$client_id);


			$this->convertResponse($response);

		}


	}
	public function commentDisease($id){

		$this->setValidVerbs(array('GET','POST','PUT'));
		$this->errorResponse();

		$request_method = $this->getHttpVerb();

		if($request_method == "POST"){

			$data = json_decode(file_get_contents("php://input"),true);

			$disease_id = $id;
			$client_id = $data['client_id'];
			$comment = $data['comment'];

			$response = $this->commentDiseaseSave($disease_id,$comment,$client_id);

			$this->convertResponse($response);

		}


	}

	public function ratingDiseaseSave($id,$value,$client_id){

		$feedback = new Feedback();
		$response = $feedback->ratingDiseaseSave($id,$value,$client_id);

		return $response;

	}
	public function ratingDiseaseUpdate($id,$value,$client_id){

		$feedback = new Feedback();
		$response = $feedback->ratingDiseaseUpdate($id,$value,$client_id);

		return $response;

	}
	public function ratingDiseaseGet($id,$client_id){

		$feedback = new Feedback();
		$response = $feedback->ratingDiseaseGet($id,$client_id);

		return $response;
	}
	public function commentDiseaseSave($id,$value,$client_id){

		$feedback = new Feedback();
		$response = $feedback->commentDiseaseSave($id,$value,$client_id);

		return $response;

	}
	public function commentDiseaseUpdate($id,$value,$client_id){

		$feedback = new Feedback();
		$response = $feedback->commentDiseaseUpdate($id,$value,$client_id);

		return $response;

	}
	public function commentDiseaseGet($id,$client_id){

		$feedback = new Feedback();
		$response = $feedback->commentDiseaseGet($id,$client_id);

		return $response;
	}

	#####################################################################################
	#MER TERMS


     public function ratingDiseaseArticle($id) {

        $this->setValidVerbs(array('GET','POST','PUT'));
		$this->errorResponse();

		$request_method = $this->getHttpVerb();

		if($request_method == "PUT"){

			$data = json_decode(file_get_contents("php://input"),true);

			$article_id = $id;
			$term = $data['term'];
			$pos_start = $data['pos_start'];
            $type = $data['type'];


            if ($type == "like") {

			 $response = $this->ratingDiseaseArticleLike($article_id, $term, $pos_start);
             $this->convertResponse($response);

            }

            else {

			    $response = $this->ratingDiseaseArticleDislike($article_id, $term, $pos_start);
                $this->convertResponse($response);
            }

		}
	}


    public function ratingDiseaseArticleLike($article_id, $term,$pos_start){

		$feedback = new Feedback();
		$response = $feedback->ratingDiseaseArticleLike($article_id, $term,$pos_start);

		return $response;

	}


    public function ratingDiseaseArticleDislike($article_id, $term,$pos_start){

		$feedback = new Feedback();
		$response = $feedback->ratingDiseaseArticleDislike($article_id, $term,$pos_start);

		return $response;

	}

  public function implicitFeedbackArticle($article_id){

    $feedback = new Feedback();
    $response = $feedback->implicitFeedbackArticle($article_id);

    return $response;

  }



}
?>

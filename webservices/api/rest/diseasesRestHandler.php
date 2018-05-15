<?php
require_once("simpleRest.php");
require_once("../models/disease.php");
require_once("../models/articles.php");

class DiseasesRestHandler extends SimpleRest {
	private $searchOptions = array('fields,limit');

	public function __construct(){
		$this->setValidVerbs(array('GET'));
		parent::__construct();
	}

	function getAllDiseases() {

		$diseases = new Disease();
		$rawData = $diseases->getDiseases();

		// array_walk_recursive($rawData, function(&$value) {
		// 	$value = utf8_decode($value);
		// });

		$requestContentType = $this->getHttpContentType();

		$this->setHttpHeaders($requestContentType, 200);//200 ok

		if(strpos($requestContentType,'application/json') !== false){
			$response = $this->encodeJson($rawData);
			echo $response;
		} else if(strpos($requestContentType,'text/html') !== false){
			$response = $this->encodeHtml($rawData);
			echo $response;
		} else if(strpos($requestContentType,'application/xml') !== false){
			$response = $this->encodeXml($rawData);
			echo $response;
		}
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
		$xml = new SimpleXMLElement('<?xml version="1.0"?><diseases></diseases>');
		$this->array_to_xml( $responseData,$xml);
		return $xml->asXML();
	}

	public function getDisease($id) {

		$disease = new Disease();
		$rawData = $disease->getDisease($id);

		// array_walk_recursive($rawData, function(&$value) {
		// 	$value = utf8_encode($value);
		// });

		$requestContentType = $this->getHttpContentType();

		$this ->setHttpHeaders($requestContentType,200);

		if(strpos($requestContentType,'application/json') !== false){
			$response = $this->encodeJson($rawData);

			echo $response;
		} else if(strpos($requestContentType,'text/html') !== false){
			$response = $this->encodeHtml($rawData);
			echo $response;
		} else if(strpos($requestContentType,'application/xml') !== false){
			$response = $this->encodeXml($rawData);
			echo $response;
		}
	}
	//diseases with artilcles and photos and tweets
	public function getDiseaseMetadata($id){



		$disease = new Disease();
		$rawData = $disease->getDiseaseMetadata($id);

		// array_walk_recursive($rawData, function(&$value) {
		// 	$value = utf8_decode($value);
		// });

		$requestContentType = $this->getHttpContentType();

		$this ->setHttpHeaders($requestContentType,200);

		if(strpos($requestContentType,'application/json') !== false){
			$response = $this->encodeJson($rawData);

			echo $response;
		} else if(strpos($requestContentType,'text/html') !== false){
			$response = $this->encodeHtml($rawData);
			echo $response;
		} else if(strpos($requestContentType,'application/xml') !== false){
			$response = $this->encodeXml($rawData);
			echo $response;
		}
	}

	public function diseaseRelatedArticles($id) {

		$article = new Article();
		$rawData = $article->getArticlesDiseaseRanked($id);

		// array_walk_recursive($rawData, function(&$value) {
		// 	$value = utf8_decode($value);
		// });

		$requestContentType = $this->getHttpContentType();

		$this->setHttpHeaders($requestContentType, 200);//200 ok

		if(strpos($requestContentType,'application/json') !== false){
			$response = $this->encodeJson($rawData);
			echo $response;
		} else if(strpos($requestContentType,'text/html') !== false){
			$response = $this->encodeHtml($rawData);
			echo $response;
		} else if(strpos($requestContentType,'application/xml') !== false){
			$response = $this->encodeXml($rawData);
			echo $response;
		}
	}

	public function relatedDiseases($id) {

		$disease = new Disease();
		$rawData = $disease->getSimilarDisease($id);

		// array_walk_recursive($rawData, function(&$value) {
		// 	$value = utf8_decode($value);
		// });

		$requestContentType = $this->getHttpContentType();

		$this->setHttpHeaders($requestContentType, 200);//200 ok

		if(strpos($requestContentType,'application/json') !== false){
			$response = $this->encodeJson($rawData);
			echo $response;
		} else if(strpos($requestContentType,'text/html') !== false){
			$response = $this->encodeHtml($rawData);
			echo $response;
		} else if(strpos($requestContentType,'application/xml') !== false){
			$response = $this->encodeXml($rawData);
			echo $response;
		}
	}

	public function hidePhoto($photo_id){

    $feedback = new Feedback();
    $response = $feedback->hidePhoto($photo_id);

		$this->convertResponse($this->successResponse);

    return $response;

  }

	public function resetDiseasePhotos($id){

    $feedback = new Feedback();
    $response = $feedback->resetDiseasePhotos($id);

		$this->convertResponse($this->successResponse);

    return $response;

  }
}
?>

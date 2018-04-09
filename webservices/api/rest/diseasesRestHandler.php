<?php
require_once("simpleRest.php");
require_once("../models/disease.php");
		
class DiseasesRestHandler extends SimpleRest {

	function getAllDiseases() {	

		$diseases = new Disease();
		$rawData = $diseases->getDiseases();

		array_walk_recursive($rawData, function(&$value) {
			$value = utf8_decode($value);
		});

		if(empty($rawData)) {
			$statusCode = 404;
			$rawData = array('error' => 'No diseases found!');		
		} else {
			$statusCode = 200;
		}

		$requestContentType = $_SERVER['CONTENT_TYPE'];
		$this->setHttpHeaders($requestContentType, $statusCode);
				
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
		
		$jsonResponse = json_encode($responseData);
		return $jsonResponse;		
	}
	
	public function encodeXml($responseData) {
		// creating object of SimpleXMLElement
		$xml = new SimpleXMLElement('<?xml version="1.0"?><disease></disease>');
		foreach($responseData as $key=>$item) {
            foreach($item as $value){
                $xml->addChild($key, $value);
            }
		}
		return $xml->asXML();
	}
	
	public function getDisease($id) {

		$article = new Article();
		$rawData = $article->getDisease($id);

		if(empty($rawData)) {
			$statusCode = 404;
			$rawData = array('error' => 'No disease found!');		
		} else {
			$statusCode = 200;
		}

		$requestContentType = $_SERVER['HTTP_ACCEPT'];
		$this ->setHttpHeaders($requestContentType, $statusCode);
				
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
}
?>
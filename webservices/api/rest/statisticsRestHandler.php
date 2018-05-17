<?php
require_once("simpleRest.php");
require_once("../models/statistics.php");

class StatisticsRestHandler extends SimpleRest {
	private $searchOptions = array('fields,limit');

	public function __construct(){
		$this->setValidVerbs(array('GET'));
		parent::__construct();
	}

	function getStatistics() {

		$statistic = new Statistic();
		$rawData = $statistic->getAll();

		array_walk_recursive($rawData, function(&$value) {
			$value = utf8_decode($value);
		});

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

		$jsonResponse = json_encode($responseData,JSON_PRETTY_PRINT);
		$jsonResponse = $this->prettyPrint($jsonResponse);
		return $jsonResponse;
	}

	public function encodeXml($responseData) {
		// creating object of SimpleXMLElement
		$xml = new SimpleXMLElement('<?xml version="1.0"?><statistic></statistic>');
		$this->array_to_xml( $responseData,$xml);
		return $xml->asXML();
	}
}
?>

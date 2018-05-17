<?php
require_once("simpleRest.php");
require_once("../models/home.php");

class HomeRestHandler extends SimpleRest {


	public function __construct(){
		$this->setValidVerbs(array('GET'));
		parent::__construct();
	}

	function recalculateInvertedIndexes() {

    $home = new Home();
    $response = $home->recalculateInvertedIndexes();

    $this->convertResponse($this->successResponse);

    return $response;
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
		$xml = new SimpleXMLElement('<?xml version="1.0"?><articles></articles>');
		$this->array_to_xml( $responseData,$xml);
		return $xml->asXML();
	}
}
?>

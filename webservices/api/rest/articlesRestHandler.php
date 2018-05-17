<?php
require_once("simpleRest.php");
require_once("../models/articles.php");

class ArticlesRestHandler extends SimpleRest {
	private $searchOptions = array('terms','fields','limit');

	public function __construct(){
		$this->setValidVerbs(array('GET'));
		parent::__construct();
	}

	function getAllArticles() {

		$article = new Article();
		$rawData = $article->getArticles();

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
		$xml = new SimpleXMLElement('<?xml version="1.0"?><articles></articles>');
		$this->array_to_xml( $responseData,$xml);
		return $xml->asXML();
	}

	public function getArticle($id) {

		$article = new Article();

		$with_terms = "";
		if(isset($_GET)){

			$get_keys = array_keys($_GET);

			if(array_key_exists('terms',$_GET)){
				//verifica se não vem filtros na api que n~são validos
				if(count(array_diff(array_keys($_GET),$this->searchOptions)) == 0){
					$with_terms = $_GET['terms'];
				}
			}
		}

		$rawData = $article->getArticle($id);

		if($with_terms == 'true'){
			$rawData['terms'] = $article->getMERTerms($id);
		}

		$rawData['authors'] = explode("|", $rawData['authors']);

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
}
?>

<?php
/*
A simple RESTful webservices base class
Use this as a template and build upon it
*/
class SimpleRest {

	private $httpVersion = "HTTP/1.1";
	private $verbs = array();

	public function __construct(){
		//verifica erros e retorna-os
		//$this->errorResponse();
	}

	public function setHttpHeaders($contentType, $statusCode){

		$statusMessage = $this -> getHttpStatusMessage($statusCode);

		header($this->httpVersion. " ". $statusCode ." ". $statusMessage);
		header("Content-Type:". $contentType);
	}

	public function getHttpStatusMessage($statusCode){
		$httpStatus = array(
			100 => 'Continue',
			101 => 'Switching Protocols',
			200 => 'OK',
			201 => 'Created',
			202 => 'Accepted',
			203 => 'Non-Authoritative Information',
			204 => 'No Content',
			205 => 'Reset Content',
			206 => 'Partial Content',
			300 => 'Multiple Choices',
			301 => 'Moved Permanently',
			302 => 'Found',
			303 => 'See Other',
			304 => 'Not Modified',
			305 => 'Use Proxy',
			306 => '(Unused)',
			307 => 'Temporary Redirect',
			400 => 'Bad Request',
			401 => 'Unauthorized',
			402 => 'Payment Required',
			403 => 'Forbidden',
			404 => 'Not Found',
			405 => 'Method Not Allowed',
			406 => 'Not Acceptable',
			407 => 'Proxy Authentication Required',
			408 => 'Request Timeout',
			409 => 'Conflict',
			410 => 'Gone',
			411 => 'Length Required',
			412 => 'Precondition Failed',
			413 => 'Request Entity Too Large',
			414 => 'Request-URI Too Long',
			415 => 'Unsupported Media Type',
			416 => 'Requested Range Not Satisfiable',
			417 => 'Expectation Failed',
			500 => 'Internal Server Error',
			501 => 'Not Implemented',
			502 => 'Bad Gateway',
			503 => 'Service Unavailable',
			504 => 'Gateway Timeout',
			505 => 'HTTP Version Not Supported');
		return ($httpStatus[$statusCode]) ? $httpStatus[$statusCode] : $status[500];
	}

	public function getHttpContentType(){

		if(!array_key_exists('CONTENT_TYPE',$_SERVER)){
			$requestContentType = 'application/json';
		}
		else{
			$requestContentType = $_SERVER['CONTENT_TYPE'];
		}

		return $requestContentType;

	}

	public function getStatusHttpContentType($requestContentType){

		if($requestContentType!='application/json' && $requestContentType!='text/html' && $requestContentType!='application/xml' && $requestContentType !='application/x-www-form-urlencoded'){
			$status = 406;
		}
		else{
			$status = 200;
		}

		return $status;
	}

	public function getHttpVerb(){

		if(array_key_exists('REQUEST_METHOD',$_SERVER)){
			return $_SERVER['REQUEST_METHOD'];
		}
		else{
			return 'UNKNOWN';
		}
	}

	public function getStatusHttpVerb($listValidVerbs){

		if(!is_array($listValidVerbs)){
			throw new Excepetion('listValidVerbs is not an array');
		}
		$verb = $this->getHttpVerb();
		if(in_array($verb,$listValidVerbs)){
			return 200;
		}else{
			return 405;
		}
	}
	public function setValidVerbs($listVerbs){

		if(!is_array($listVerbs)){
			throw new Excepetion('listVerbs is not an array');
		}

		foreach($listVerbs as $verb){
			$this->verbs[] = $verb;
		}

	}
	public function errorResponse(){

		$requestContentType = $this->getHttpContentType();

		$statusCodeHttpContentType =  $this->getStatusHttpContentType($requestContentType);

		$statusCodeHttpVerb =  $this->getStatusHttpVerb($this->verbs);

		if($statusCodeHttpContentType != 200){
			$this->setHttpHeaders($requestContentType, $statusCodeHttpContentType);
			echo json_encode(array('error' => 'Not a valid ContentType -> '.$requestContentType));
			die();
		}

		if($statusCodeHttpVerb != 200){
			$this->setHttpHeaders($requestContentType, $statusCodeHttpVerb);
			echo json_encode(array('error' => 'Not a valid Http verb -> '.$this->getHttpVerb()));
			die();
		}

	}
	public function array_to_xml( $data, &$xml_data ) {
		foreach( $data as $key => $value ) {
			if( is_numeric($key) ){
				$key = 'item'.$key; //dealing with <0/>..<n/> issues
			}
			if( is_array($value) ) {
				$subnode = $xml_data->addChild($key);
				$this->array_to_xml($value, $subnode);
			} else {
				$xml_data->addChild("$key",htmlspecialchars("$value"));
			}
		 }
	}

    public function convertResponse($rawData){

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
		}else if(strpos($requestContentType,'application/x-www-form-urlencoded') !== false){
			$response = $this->encodeJson($rawData);
			echo $response;
		}


    }

		public function prettyPrint( $json ) {
	    $result = '';
	    $level = 0;
	    $in_quotes = false;
	    $in_escape = false;
	    $ends_line_level = NULL;
	    $json_length = strlen( $json );

	    for( $i = 0; $i < $json_length; $i++ ) {
	        $char = $json[$i];
	        $new_line_level = NULL;
	        $post = "";
	        if( $ends_line_level !== NULL ) {
	            $new_line_level = $ends_line_level;
	            $ends_line_level = NULL;
	        }
	        if ( $in_escape ) {
	            $in_escape = false;
	        } else if( $char === '"' ) {
	            $in_quotes = !$in_quotes;
	        } else if( ! $in_quotes ) {
	            switch( $char ) {
	                case '}': case ']':
	                    $level--;
	                    $ends_line_level = NULL;
	                    $new_line_level = $level;
	                    break;

	                case '{': case '[':
	                    $level++;
	                case ',':
	                    $ends_line_level = $level;
	                    break;

	                case ':':
	                    $post = " ";
	                    break;

	                case " ": case "\t": case "\n": case "\r":
	                    $char = "";
	                    $ends_line_level = $new_line_level;
	                    $new_line_level = NULL;
	                    break;
	            }
	        } else if ( $char === '\\' ) {
	            $in_escape = true;
	        }
	        if( $new_line_level !== NULL ) {
	            $result .= "\n".str_repeat( "\t", $new_line_level );
	        }
	        $result .= $char.$post;
	    }

	    return $result;
	}
}
?>

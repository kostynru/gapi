<?php
error_reporting(E_ALL);
final class GAPI {
//Initialising base variables
	private $error_last;
	private $server;
	private $app_id;
	private $app_token;
	private $response;
//Main methods
	public function __construct($server, $app_id, $app_key, $response = 'xml'){
		$result = file_get_contents("http://$server/api/auth?app_id=$app_id&app_key=$app_key&response=$response");
		if(!$result){
			$err = 'Authentication failed';
			$this->error_last = $err;
		} else {
			$this->server = $server;
			$this->response = $response;
			if($response == 'xml'){
				$xml = new SimpleXMLElement($result);
				$token = $xml->auth->answer;
				$this->app_token = $token;
			} elseif($response == 'json'){
				$answer = json_decode($result);
				$this->app_token = $answer->token;
			} else {
				trigger_error('Invalid response type', E_USER_ERROR);
				die;
			}
		}
	}
	public function query($space, $method, $params = array()){
			$url_params = http_build_query($params);
			$token = $this->app_token;
			$server_url = $this->server;
			if(!empty($params)){
				$url_params = '&'.$url_params;
			}
			$url = "http://$server_url/api/$space.$method?token=$token"."$url_params";
			$result = file_get_contents($url);
			if($this->response == 'xml'){
				$xml = simplexml_load_string($result);
				return $xml;
			} elseif($this->response == 'json') {
				$json = json_decode($result, true);
				return $json;
			}
	}
	public function getToken(){
		return $this->app_token;
	}
	public function getLastError(){
		return $this->error_last;
	}
	public function close_session(){
		$token = $this->app_token;
		$server_url = $this->server;
		$url = "http://$server_url/api/application.close_session?token=$token";
		return file_get_contents($url);
	}
}
	
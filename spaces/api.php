<?php
/*
This's the main api class. Please, don't edit or delete it.
*/
include 'base_class.php';
class Api extends ApiBase{
	public function get_info(){
		$sp_params = $_GET;
		$answer = array();
		$query = "SELECT * FROM api_info";
		$result = mysql_query($query) or die(mysql_error());
		while($row = mysql_fetch_assoc($result)){
			$answer = $row;
		}
		return $answer;
	}
	public function server(){
		return $_SERVER['SERVER_NAME'];
	}
	public function about(){
		return ApiBase::$about_array;
	}
}
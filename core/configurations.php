<?php
class Configuration {
	function __construct(){
		$this->http_url = ($_SERVER['HTTP_HOST'] == "localhost" || $_SERVER['HTTP_HOST'] == "192.168.0.7" || $_SERVER['HTTP_HOST'] == "danielbgomez.com") ? "http://".$_SERVER['HTTP_HOST']."/Destroy-The-Nexus/" : "http://".$_SERVER['HTTP_HOST']."/";
		$this->disk_url = ($_SERVER['HTTP_HOST'] == "localhost" || $_SERVER['HTTP_HOST'] == "192.168.0.7" || $_SERVER['HTTP_HOST'] == "danielbgomez.com") ? $_SERVER['DOCUMENT_ROOT'].'/Destroy-The-Nexus/' : $_SERVER['DOCUMENT_ROOT'].'/';

		$this->currentseason = "SEASON2016";
		$this->version = "6.8.1";
		$this->region = "LAN";
		$this->locale = "en_US";

		$this->api_key="api_key=c2b167ae-270e-40af-ad19-85db8e5d0420";
		
		$this->regionid = array('BR'=>'BR1','EUNE'=>'EUN1','EUW'=>'EUW1','KR'=>'KR','LAN'=>'LA1','LAS'=>'LA2','NA'=>'NA1','OCE'=>'OC1','TR'=>'TR1','RU'=>'RU','PBE'=>'PBE1','Global'=>'*');
		$this->regionstr = array('BR' => 'Brazil', 'EUNE' => 'EU Nordic & East', 'EUW'=>'EU West', 'KR'=>'Korea', 'LAN'=>'Latin America North', 'LAS'=>'Latin America South', 'NA'=>'North America', 'OCE'=>'Oceania','TR'=>'Turkey','RU'=>'Russia','PBE'=>'Public Beta Enviroment','Global'=>'Global');
		$this->regionhost = array('BR'=>'br.api.pvp.net','EUNE'=>'eune.api.pvp.net','EUW'=>'euw.api.pvp.net','KR'=>'kr.api.pvp.net','LAN'=>'lan.api.pvp.net','LAS'=>'las.api.pvp.net','NA'=>'na.api.pvp.net','OCE'=>'oce.api.pvp.net','TR'=>'tr.api.pvp.net','RU'=>'ru.api.pvp.net','PBE'=>'pbe.api.pvp.net','Global'=>'global.api.pvp.net' );

		$this->url_ddragon = "http://ddragon.leagueoflegends.com/cdn/";
		$this->url_img = $this->url_ddragon."img/";
		$this->url_img_version = $this->url_ddragon.$this->version."/"."img/";

		$this->response_code_api = array('400' => array('error' => '400', 'name' => 'Bad Request', 'description' => 'There is a syntax error in the request and the request has therefore been denied. The client should not continue to make similar requests without modifying the syntax or the requests being made.'),'401' => array('error' => '401', 'name' => 'Unauthorized', 'description' => 'The API request being made did not contain the necessary authentication credentials and therefore the client was denied access. If authentication credentials were already included then the Unauthorized response indicates that authorization has been refused for those credentials.'),'403' => array('error' => '403', 'name' => 'Forbidden', 'description' => ''),'404' => array('error' => '404', 'name' => 'Not Found', 'description' => 'The server has not found a match for the API request being made. No indication is given whether the condition is temporary or permanent.'),'415' => array('error' => '415', 'name' => 'Unsupported Media Type', 'description' => 'The server is refusing to service the request because the body of the request is in a format that is not supported.'),'429' => array('error' => '429', 'name' => 'Rate Limit Exceeded', 'description' => 'The application has exhausted its maximum number of allotted API calls allowed for a given duration. If the client receives a Rate Limit Exceeded response the client should process this response and halt future API calls for the duration, in seconds, indicated by the Retry-After header. Due to the increased frequency of clients ignoring this response, applications that are in violation of this policy may be disabled to preserve the integrity of the API.'),'500' => array('error' => '500', 'name' => 'Internal Server Error', 'description' => 'An unexpected condition or exception which prevented the server from fulfilling an API request.'),'503' => array('error' => '503', 'name' => 'Service Unavailable', 'description' => 'The server is currently unavailable to handle requests because of an unknown reason. The Service Unavailable response implies a temporary condition which will be alleviated after some delay.'));
	}

	function __destruct(){unset($this);}
}
?>
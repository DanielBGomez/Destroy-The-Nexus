<?php
header('Content-type: text/html; charset=utf-8');
	require('core/configurations.php');
	class Database {
		protected function get_from_query(){
			return false;
		}
		protected function execute_query(){
			return true;
		}
	}
	class Core extends Database {
		public $console_log;
		function __construct(){ }
		public function sortArray($arr, $col, $k = '', $dir = SORT_ASC) {
			error_reporting(0);
			$sort_col = array();
			foreach ($arr as $key => $row) {
				if($k != ''){
					$sort_col[$row[$k]] = $row[$col];
				} else {
					$sort_col[$key] = $row[$col];
				}
			}
			array_multisort($sort_col, $dir, $arr);
			return $arr;
		}
		protected function getDataFromAPI($region = 'LAN',$case = 0,$data = 'all',$params = array() ){
			$this->console_log .= 'Data from API request.\n';
			$data = ($data == '') ? 'all' : $data; // Si '$data' está vacio, se considera como 'all'
			if(is_array($data)){
				foreach ($data as $value){
					$datatmp = (isset($datatmp)) ? $datatmp.','.$value : $value;
				}
				$data = $datatmp;
				unset($datatmp);
			}
			$params = ($params == "") ? array() : $params;	// Si '$params' contiene una cadena, convertirla en arreglo
			if(count($params)) {
				foreach ($params as $key => $value) {
					$paramsString = (isset($paramsString)) ? "$paramsString&$key=$value" : "?$key=$value";
				}
				$paramsString .= '&'.$this->conf->api_key;
			} else { $paramsString='?'.$this->conf->api_key; }
			$params = $paramsString;
			unset($paramsString);
			$region = strtolower($region);	// Convertir '$region' a minusculas para evitar conflictos
			$case = preg_split("/\//", $case);	// Corta y convierte en arreglo la cadena en '$case' cada que se encuentre un "/" 
			// URL inicial de la consulta a la API
			$apiurl = "https://$region.api.pvp.net/api/lol/$region/";
			$this->console_log .= (isset($case[1])) ? ' - '.$case[0]. ' -> '.$case[1].'.\n\n' : ' - '.$case[0];
			
			switch($case[0]){ // Qué es lo que se quiere consultar?
				case 'static':
					$apiurl = "https://global.api.pvp.net/api/lol/static-data/$region/v1.2/".$case[1]; // Static Data se consulta en el servidor Global
					$complex = array('champion','item','mastery','rune','summoner-spell');
					if(in_array($case[1], $complex)){ 
						if(strtolower($data) == 'all' || intval($data) == 0){ // Si 
							$apiurl .= $params;
						} else {
							$apiurl .= "/". $data . $params;
						}
					} else {
						$apiurl .= $params;
					}
					break;
				case 'summoner':
					$apiurl .= "v1.4/summoner/";
					$complex = array('masteries','name','runes');
					if(isset($case[1])){
						if(in_array($case[1], $complex)){
							$apiurl .= $data .'/'. $case[1] . $params;
						} else {
							$apiurl .= $case[1] .'/'. $data . $params;
						}
					} else {
						$apiurl .= $data . $params;
					}
					break;
				case 'league':
					$apiurl .= "v2.5/league/";
					$complex = array('by-summoner','by-team');
					if(isset($case[1])){
						$apiurl .= $case[1].'/'.$data;
						if(in_array($case[1], $complex) && isset($case[2])){
							$apiurl .= '/' . $case[2] . $params;
						} else {
							$apiurl .= $params;
						}
					} else {
						return array('error' => array('error' => '400', 'name' => 'Bad Request'));
					}
					break;
				case 'stats':
					if(isset($case[1])){
						$apiurl .= "v1.3/stats/by-summoner/" . $data ."/". $case[1] . $params;
					} else {
						return array('error' => array('error' => '400', 'name' => 'Bad Request'));
					}
					break;
				case 'livegame':
					$apiurl = "https://$region.api.pvp.net/observer-mode/rest/consumer/getSpectatorGameInfo/".$this->conf->regionid[strtoupper($region)]."/$data".$params;
					break;
				case 'championmastery':
					if(isset($case[1])){
						$data = preg_split("/,/", $data);
						$apiurl = "https://$region.api.pvp.net/championmastery/location/".$this->conf->regionid[strtoupper($region)]."/player/".$data[0]."/".$case[1];
						if($case[1] == 'champion'){
							if(isset($data[1])){
								$apiurl.= '/'.$data[1];
							} else {
								return array('error' => array('error' => '400', 'name' => 'Bad Request'));
							}
						}
						$apiurl .= $params;
					} else {
						return array('error' => array('error' => '400', 'name' => 'Bad Request'));
					}
					break;
				default:
					return array('error' => array('error' => '400', 'name' => 'Bad Request'));
					break;
			}
			if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_GET['request'])){
				$this->console_log .= 'API Request From script.\n\n';
			} else {
				// Controlar bucle con el archivo
				$i = 0;
				$loop = true;
				$this->console_log .= 'API Request From file.\n\n'; // Insertar evento en Log.
				// Contexto para ignorar los errores en el 'file_get_contents()'
				$context = stream_context_create(array(
   	 			'http' => array('ignore_errors' => true),
				));
				do {
					$this->console_log .= "Getting data From Api ( ". ($i+1) .' )\n';
					$json = file_get_contents($apiurl,false,$context);
					$data = json_decode($json,true);
					if(isset($data['status'])){
						if($data['status']['status_code'] != '429'){
							$loop = false; // Si el error no es de Request Limit, salir del bucle
						}
					} elseif (!empty($data) || $http_response_header[0] != 'HTTP/1.1 200 OK') {
						$loop = false; // Si no existe un status y existen datos, salir del bucle
					}
					$i++;
				} while($loop && $i < 5);
				if($i > 4){
					// Timeout
					$this->console_log .='Request Timeout!\n\n';
					return array('error' => array('error' => '408', 'name' => 'Timeout'));
				} elseif (isset($data['status'])){
					// Error
					$this->console_log .= 'Error in the Request!\n\n';
					return array('error' => $this->conf->response_code_api[$data['status']['status_code']]);
				} elseif ($http_response_header[0] != 'HTTP/1.1 200 OK') {
					// Error
					$error = preg_split("/ /", $http_response_header[0]);
					$this->console_log .= 'Error in the Request!\n\n';
					return array('error' => array('error' => $error[1], 'name'=> $error[2], 'description' => $error[2] ));
				} else {
					// Ok
					$this->console_log .= 'Data Handled!\n\n';
					return $data;
				}
			}
		}
		public function getSummonerIdsByName($names, $region = 'LAN'){
			$this->console_log .= 'Getting Summmoner(s) ID\'s by Name(s)...\n\n';
			$namesArray = preg_split("/,/", $names); unset($names);
			$ids = array();
			$this->console_log .= ' - Search for the ID\'s in the Database...\n';
			$i = 0;
			foreach ($namesArray as $name) {
				$this->query = "SELECT `id`,`namesearch` FROM summoners WHERE namesearch = '$name' AND region='$region';";
				if($this->get_from_query() && !empty($this->rows[0])){
					$this->console_log .= '    - \''.$name.'\' ( '.$this->rows[0]['id'].' ) Found!\n';
					$ids[$this->rows[0]['namesearch']] = $this->rows[0]['id'];
					unset($namesArray[$i]);
					$this->console_log .= '       - Removed from API Query!\n';
				} else {
					$this->console_log .= '    - \''.$name.'\' Not Found!\n';
				}
				$i++;
			}
			$this->console_log .= '\n';
			if(!empty($namesArray)){
				$this->console_log .= ' - Search for the ID\'s remaining in the API Query...\n\n';
				foreach ($namesArray as $name) {
					$names = (isset($names)) ? $names.','.$name : $name;
				}
			}
			if(isset($names)) {
				$data = $this->getDataFromAPI($region,'summoner/by-name',str_replace(" ", "", $names));
				if(isset($data['error'])){
					$this->console_log .= ' - '.$data['error']['error'].' - '.$data['error']['name'].'\n\n';
					return false;
				} else {
					$this->console_log .= 'Storing in Database...\n\n'.
										  ' - Proccessing the Values...\n';
					foreach ($data as $name => $single) {
						$ids[$name] = $single['id'];
						$single['region'] = $region;
						$insertColumns = 'namesearch,updatedate';
						$values = "'$name','".time()."'";
						foreach ($single as $key => $value) {
							$insertColumns .= ",`$key`";
							$values .=  ",'$value'";
						}
						$insertValues = (isset($insertValues)) ? $insertValues.",($values)" : "($values)";
						unset($values);
					}
					$this->console_log .= '    - Values Proccessed! ( '.count($data).' )\n\n'.
										  ' - Storing Values in Database...\n';
					$this->query = "INSERT INTO summoners ($insertColumns) VALUES $insertValues;";
					if($this->execute_query()){
						$this->console_log .= '    - Values Stored!\n\n';
					} else {
						$this->console_log .= '    - Can\'t Store the Values!\n\n';
					}
				}
			}
			if(!empty($ids)) {
				$this->console_log .= ' - Returning IDs.\n\n';
				return $ids;
			} else {
				$this->console_log .= ' - Something Went Wrong While Returning the IDs!\n\n';
				return false;
			}
		}
		public function getImageFile($url,$type){
			$file = preg_split("/\//",$url);
			$file = end($file);
			$ext = preg_split("/\./", $file);
			$ext = end($ext);
			$imageTypes = array('png','gif','jpg','jpeg');
			$rutadisk = ($type == 'tiermedal' || $type == 'map' || $type == 'sprite' || $type == 'mastery' || $type == 'rune') ? $this->conf->disk_url.'src/img/' : $this->conf->disk_url.'src/img/'.$this->conf->version.'/';
			$rutaurl = ($type == 'tiermedal' || $type == 'map' || $type == 'sprite' || $type == 'mastery' || $type == 'rune') ? $this->conf->http_url.'src/img/' : $this->conf->http_url.'src/img/'.$this->conf->version.'/';
			if(!in_array($ext, $imageTypes)){
				return str_replace(" ","%20",$rutaurl.$type.'/404.png');
			}
			if(!file_exists($rutadisk.$type.'/'.$file)){
				$return = $this->subir($file,$url,$type);
				if($return == '404' && $type == 'summonericon'){
					return str_replace(" ","%20",$rutaurl.$type.'/404.png');
				} else { 
					return $return;
				}
			} else {
				return str_replace(" ","%20",$rutaurl.$type.'/'.$file);
			}
		}
		private function compress_image($source_url, $destination_url, $quality = 85) {
			$context = stream_context_create(array(
   	 		'http' => array('ignore_errors' => true),
			));
			file_get_contents($source_url,false,$context);
			if($http_response_header[0] != 'HTTP/1.1 200 OK'){ return false; }
			$info = getimagesize($source_url);
 
			if ($info['mime'] == 'image/jpeg') $image = imagecreatefromjpeg($source_url);
			elseif ($info['mime'] == 'image/gif') $image = imagecreatefromgif($source_url);
			elseif ($info['mime'] == 'image/png') $image = imagecreatefrompng($source_url);
 
			//save file
			imagejpeg($image, $destination_url, $quality);
 
			//return destination file
			return $destination_url;
		}
		private function subir($name,$imagen,$type){ ;
			if(!($type == 'tiermedal' || $type == 'map' || $type == 'sprite' || $type == 'mastery' || $type == 'rune')){ $type = $this->conf->version .'/'. $type; }
			$rutaurl = $this->conf->http_url.'src/img/';
			$ruta = $this->conf->disk_url.'src/img/';
			// Verificar si existe la carpeta para el tipo de imagen ("$type") y en caso de no existir, la crea.
			foreach(preg_split("/\//", $type) as $t){
				$ruta .= $t.'/';
				if(!file_exists($ruta)) {
					mkdir($ruta, 0777);
				}
			}
			$file_headers = @get_headers($imagen);
			if($file_headers[0] == 'HTTP/1.1 404 Not Found') {
			    return '404';
			} else {
				if($type == 'rune'){
					if(file_put_contents($ruta.$name, file_get_contents($imagen)) ){
						return str_replace(" ","%20",$rutaurl.$type.'/'.$name);
					} else {
						return '404';
					}
				} else {
					if($this->compress_image($imagen,$ruta.$name) ){
						return str_replace(" ","%20",$rutaurl.$type.'/'.$name);
					} else {
						return '404';
					}
				}
			}
		}
		function __destruct(){ unset($this); }
	}
<?php
	class destroythenexus extends Core {
		function __construct(){
			$this->conf = new Configuration();
			$this->showFiles = true;

			if(isset($_GET['case']) && $_GET['case'] == 'verifyUser'){
				if(!isset($_GET['summoner']) || !isset($_GET['region'])){
					header('Location: '.$this->conf->http_url.'destroythenexus/');
				}
				setcookie('error',false, time()-1);
				
				$summoner = strtolower(str_replace(" ","",$_GET['summoner']));
				$region = $_GET['region'];
				$regionId = (isset($this->conf->regionid[$region])) ? $this->conf->regionid[$region] : false;
				if(!$regionId){
					header('Location: '.$this->conf->http_url.'destroythenexus/');
				}
				setcookie("region",$_GET['region']);
				$ids = $this->getSummonerIdsByName($summoner,$this->conf->region);
				$summonerId = (isset($ids[$summoner])) ? $ids[$summoner] : 0;
				if($summonerId){
					setcookie("summoner",json_encode(array('id'=>$summonerId,'name'=>$summoner)));
					if(!isset($_COOKIE['champions'])){
						$data = $this->getDataFromAPI($region,'championmastery/champions',$summonerId);
						if(isset($data['error'])){
							setcookie('error',"Something went wrong while trying to receive the champions data!<br>Please try again later!");
							header('Location: '.$this->conf->http_url.'destroythenexus/');exit;
						}
						if(count($data) < 10){
							setcookie('error',"You must have at least 10 champions with points to play!");
							header('Location: '.$this->conf->http_url.'destroythenexus/');exit;
						}
						$data = array_slice($data,0,10);
						$context = stream_context_create(array(
   		 					'http' => array('ignore_errors' => true),
						));
						$champs = json_decode(file_get_contents($this->conf->http_url.'src/json/allchamps-id_key_name_title.json',false,$context),true);
						$champions = array();
						foreach ($data as $v) {
							$champions[$v['championId']] = array(
								'data' => $champs[$v['championId']],
								'championLevel' => $v['championLevel'],
								'championPoints' => $v['championPoints']
							);
						}
						if(empty($champions)){
							setcookie('error',"Something went wrong while trying to receive the champions data!<br>Please try again later!");
							header('Location: '.$this->conf->http_url.'destroythenexus/');exit;
						}
						$championsJson = json_encode($champions);
						setcookie("champions",$championsJson);
					}
					setcookie("score",0);
					setcookie("level",1);
					header('Location: '.$this->conf->http_url.'destroythenexus/?game');
					$this->showFiles = false;
				} else {
					setcookie('error',"Can't find the summoner <b>".$summoner."</b>!<br>Make sure the name and region are correct.");
					header('Location: '.$this->conf->http_url.'destroythenexus/');exit;
				}
			}

			if($_SERVER['REQUEST_METHOD'] == 'POST'){
				$this->showFiles = false;
				if(!isset($_GET['case'])){
					http_response_code(400);exit;
				}

				$case = $_GET['case'];
				switch ($case) {
					case 'index':
						setcookie("score",false,time()-1);
						setcookie("gameOver",false,time()-1);
						setcookie("champions",false,time()-1);
						setcookie("level",false,time()-1);
						require('events/summonerForm.php');
						break;
					case 'game':
						if(!isset($_COOKIE['summoner']) || !isset($_COOKIE['champions']) || !isset($_COOKIE['score']) || !(isset($_COOKIE['level'])) ){
							http_response_code(400);exit;
						}
						if(isset($_COOKIE['gameOver'])){
							header('Location: '.$this->conf->http_url.'destroythenexus/');exit;
						}

						$this->summoner = json_decode($_COOKIE['summoner'], true);
						$this->summonerChamps = json_decode($_COOKIE['champions'],true);
						$this->score = $_COOKIE['score'];
						$this->level = $_COOKIE['level'];
						require('events/game.php');
						break;
					case 'next':
						if(!isset($_COOKIE['summoner']) || !isset($_COOKIE['champions']) || !isset($_COOKIE['score']) ||  !(isset($_COOKIE['level'])) ){
							http_response_code(400);exit;
						}
						setcookie('level', $_COOKIE['level'] + 1);
						$this->summoner = json_decode($_COOKIE['summoner'], true);
						$this->summonerChamps = json_decode($_COOKIE['champions'],true);
						$this->score = $_COOKIE['score'];
						$this->level = $_COOKIE['level']+1;
						require('events/game.php');
						break;
					case 'gameOver':
						if(!isset($_COOKIE['summoner']) || !isset($_COOKIE['level']) || !isset($_COOKIE['score'])){
							http_response_code(400);exit;
						}
						setcookie('gameOver',true);
						$this->summoner = json_decode($_COOKIE['summoner'], true);
						$this->score = $_COOKIE['score'];
						$this->level = $_COOKIE['level'];
						require('events/gameOver.php');
						break;
					case 'submitScore':
						if(!isset($_COOKIE['summoner']) || !isset($_COOKIE['level']) || !isset($_COOKIE['score'])){
							http_response_code(400);exit;
						}
						$this->summoner = json_decode($_COOKIE['summoner'], true);
						$this->score = $_COOKIE['score'];
						$this->level = $_COOKIE['level'];
						$context = stream_context_create(array(
   		 					'http' => array('ignore_errors' => true),
						));
						$scores = json_decode(file_get_contents($this->conf->http_url.'src/json/destroythenexus-scores.json',false,$context),true);
						$scores[$this->summoner['name']] = (!isset($scores[$this->summoner['name']]) || $scores[$this->summoner['name']]['score'] < $this->score) ? array('name' => $this->summoner['name'], 'level' => $this->level, 'score' => $this->score ) : $scores[$this->summoner['name']];
						$scores = $this->sortArray($scores, 'score', $k = 'name', SORT_DESC);
						$fo = fopen($this->conf->disk_url.'src/json/destroythenexus-scores.json','w+');
						fwrite($fo, json_encode($scores));
						fclose($fo);
						$this->scores = $scores;
						require('events/scores.php');
						break;
					case 'scores':
						$context = stream_context_create(array(
   		 					'http' => array('ignore_errors' => true),
						));
						$this->scores = json_decode(file_get_contents($this->conf->http_url.'src/json/destroythenexus-scores.json',false,$context),true);
						require('events/scores.php');
						break;
					default:
						http_response_code(400);
						break;
				}
			}
		}

		function __destruct(){ unset($this); }
	}

	$core = new destroythenexus();
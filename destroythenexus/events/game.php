<?php
	if(!isset($this->summoner) || !isset($this->summonerChamps) || !isset($this->score) || !(isset($this->level)) ){
		http_response_code(400);exit;
	}

	$top10 = array_slice($this->summonerChamps, 0, 10);
	$selected = (!isset($top10[10-$this->level])) ? array_shift($top10) : $top10[10-$this->level];
	$context = stream_context_create(array(
   		'http' => array('ignore_errors' => true),
	));
	$data = json_decode(file_get_contents($this->conf->http_url.'src/json/champ'.$selected['data']['id'].'-all.json',false,$context),true);
	if(empty($data)){
		http_response_code(503);exit;
	}
	$selected['spells'] = array(json_decode(strip_tags($data['spells_0']),true),json_decode(strip_tags($data['spells_1']),true),json_decode(strip_tags($data['spells_2']),true),json_decode(strip_tags($data['spells_3']),true));
	$kToSpellKey = array('Q','W','E','R');
?>
<script type="text/javascript" src="<?php echo $this->conf->http_url; ?>src/js/js-cookie.js"></script>
<style type="text/css">
	#content {width: 94%;margin:0px 3%;}
	#game {width: 1000px;height: 420px;margin:0px auto;background-color: rgba(255,255,255,0.5);background-image: url('<?php echo $this->conf->http_url; ?>src/img/back-dtn-min.jpg');box-shadow: 0px 0px 5px 5px #000;
		background-size: 100%;background-size: cover;background-position: center;overflow: hidden;}
	#playArea {display: inline-block;float: left;margin: 40px 20px}
	#spells, #rail{float:left;padding: 20px;background-color: rgba(255,255,255,0.5);background-size: auto 110%;background-position: center;box-shadow: 0px 0px 5px 3px rgba(0,0,0,0.5)}
	#rail {width: 520px;padding: 20px 0px;}
	#spells li,#rail div {width:40px; height: 40px;margin: 2px;padding: 8px 20px 8px 0px;text-align: left}
	#rail div {padding: 8px 0px;width: 100%}
	#rail img {opacity: 0.1;position: absolute;margin-left:475px;}
	#spells li {cursor: pointer;}
	#spells li span {position: absolute;width: 40px;margin-left: -40px;font-weight: 700;text-shadow:0px 0px 5px #000;margin-top:22px;text-align: left}
	#spells img, #rail img  {width: 40px;height: 40px;box-shadow: 0px 0px 5px 5px #000;}
	#spells li:focus, #spells li:hover {opacity: 0.6}

	#playArea .health {margin-top: 290px;width: 90%}
	#playArea #score {text-align: left;font-size: 20px;margin: 15px 0px 0px 10px}

	#nexus {position: absolute;height: 100%;margin: 50px 0px 0px 670px}
	#nexus .health {width:  250px;}
	#nexus img {height: 300px;margin: 0px auto;}
	#levelAnnouncer {font-size: 50px;display: none}

	.health {border-radius: 5px;margin:0px auto;overflow: hidden;border:#ccc 1px solid;height:15px;box-shadow: 0px 0px 5px 2px #000;}
	.health .ammount {width: 100%;height: 100%;background-color: rgba(0,180,50,0.9);font-size: 12px;font-family: monospace;}

	.type {position: absolute;opacity: 0;width: 40px;font-weight: 700}
</style>

<h1 id="levelAnnouncer" class="bangers">LEVEL <?php echo $this->level; ?></h1>

<div id="game">
	<div id="playArea">
		<ul id="spells" style="background-image: url('<?php echo $this->getImageFile($this->conf->url_img.'champion/loading/'.$selected['data']['key'].'_0.jpg', 'champion/loading'); ?>')">
			<?php foreach ($selected['spells'] as $k => $spellData) {
				$img = $spellData['image']['full'];?>
				<li><img src="<?php echo $this->getImageFile($this->conf->url_img_version.'spell/'.$img, 'champion/spell'); ?>" k="<?php echo $k; ?>"><span><?php echo $kToSpellKey[$k]; ?></span></li>
			<?php } ?>
		</ul>
		<div id="rail">
			<div class="0"></div>
			<div class="1"></div>
			<div class="2"></div>
			<div class="3"></div>
		</div>

		<div class="health champHealth">
			<div class="ammount">100</div>
		</div>
		<h1 id="score">Score: <span>#</span></h1>
	</div>

	<div id="nexus">
		<div class="health"><div class="ammount"><?php echo $selected['championPoints']; ?></div></div>
		<img src="<?php echo $this->conf->http_url; ?>src/img/nexus-min.png">
	</div>
</div>

<script type="text/javascript">
	$("title").html("Level <?php echo $this->level; ?> &nbsp;::&nbsp; <?php echo $this->summoner['name']; ?> &nbsp;::&nbsp; Destroy The Nexus &nbsp;::&nbsp; Champion Master");
	$score = parseInt("<?php echo $this->score; ?>");
	$("#score span").text($score);
	$level = parseInt("<?php echo $this->level ?>");
	$interval = 2000 - ( 300 * $level - 1 );
	if($interval < 310){ $interval = 310; }
	$speed = 3000 - (300 * ( $level - 1 ) );


	if($level > 10 ){
		$("#game").fadeOut(function(){
			consultar("gameOver");
		});
	}

	$("#content").imagesLoaded().then(function(){
		$("#levelAnnouncer").fadeIn(function(){
			setTimeout(function(){
				$("#levelAnnouncer").fadeOut(function(){

					$("#game").fadeIn();
					$game = setInterval(function(){
						throwSpell();
					},$interval);
				});
			}, 2000);
		});
	});
	
	if(typeof $dontLoadFunctions == 'undefined'){

		function throwSpell(){
			$rand = Math.floor( Math.random() * 4 );
			$img = $("img[k='"+$rand+"']").attr("src");
			$("."+$rand).append('<img src="'+$img+'">');
			$("."+$rand+" img:last").animate({opacity:0.8,marginLeft: -105},$speed,function(){
				$(this).fadeOut(function(){
					$(this).remove();
					showType($rand,'Miss');
					receiveDamage();
				});
			});
		}

		$(document).keypress(function(e){
			if(e.keyCode == 113 || e.keyCode == 119 || e.keyCode == 101 || e.keyCode == 114 ){
				keyPressed(e.keyCode, 0);
			}
		});

		function keyPressed($k, $type){
			if($type == '0'){
				if($k == 113){
					$k = 0;
				}
				if($k == 119){
					$k = 1;
				}
				if($k == 101){
					$k = 2;
				}
				if($k == 114){
					$k = 3;
				}
				if(!$("."+$k+" img:first-child").length){
					$("#rail img:first").stop().remove();
					receiveDamage();
				}
				$marginLeft = parseInt( $("."+$k+" img:first-child").css("margin-left") );

				if( $marginLeft < -90 || $marginLeft >= -15  ){
					receiveDamage();
					$type = 'Missed';
				} else {
					if ( $marginLeft < -15 && $marginLeft >= -40 ){
						$type
						makeDamage(50);
						$score = $score + ( 50 + ( $level * 10 ) );
					} else if( $marginLeft < -40 && $marginLeft >= -72){
						makeDamage(100);
						$score = $score + ( 100 + ( $level * 10 ) );
					} else if( $marginLeft < -72 && $marginLeft >= -90){
						makeDamage(300);
						$score = $score + ( 300 + ( $level * 10 ) );
					}
				}
				$("#score span").text($score);
				showType($k,$type);
				$("."+$k+" img:first-child").stop().remove();
			} else {	

			}
		}

		function showType($k,$type){

		}

		function receiveDamage(){
			$championHealth = $(".champHealth .ammount");
			$hi = parseInt( $championHealth.text() ) - ( 25 - ( 2 * ( $level - 1 ) ) );
			if($hi <= 0){ $hi = 0; endGame(false); }
			$championHealth.text($hi).stop().animate({width: $hi+'%'},500);
		}

		function makeDamage($base){
			$base = parseInt($base);
			$nexusHealth = $("#nexus .health .ammount");
			$hi = parseInt( $nexusHealth.text() );
			$hw = parseInt( $nexusHealth.width() );
			$hi2 = $hi - ( $base * ( Math.floor( Math.random() * 3  ) + 1 ) );
			$hw2 = parseInt( $hi2 * $hw / $hi );
			if($hi2 <= 0 || $hw2 <= 0 ){ $hi2 = 0;$hw2 = 0; endGame(true); }
			$nexusHealth.text($hi2).stop().animate({width: $hw2+'px'},500);
		}

		function endGame($case){
			$.cookie('score',$score);
			$dontLoadFunctions = true;
			clearInterval($game);
			$("#rail img").stop().fadeOut(function(){
				$("#rail img").remove();
			});
			if($case){
				$("#nexus").animate({opacity: 0}, 3000,function(){
					setTimeout(function(){
						if($level > 9 ){
							$("#game").fadeOut(function(){
								consultar("gameOver");
							});
						} else {
							$("#game").fadeOut(function(){
								consultar("next");
							});
						}
					}, 1000);
				});
			} else {
				$("#game").fadeOut(function(){
					consultar("gameOver");
				});
			}
		}
	}
</script>
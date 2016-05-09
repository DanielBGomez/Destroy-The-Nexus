<?php
	if(!isset($this->summoner) || !isset($this->score) ){
		http_response_code(400);exit;
	}
?>
<script type="text/javascript" src="<?php echo $this->conf->http_url; ?>src/js/js-cookie.js"></script>
<style type="text/css">
	#levelAnnouncer {font-size: 50px;display: none}
	#submitScore {display: none}
		#submitScore h1 {margin-bottom: 10px;}
		input, select, option {color:#333;width:390px;font-size: 18px;padding:15px;margin:0px auto 10px;display:block;box-sizing:border-box;border-radius: 5px;border:#aaa 1px solid;box-shadow:0px 0px 5px 1px #444}
		input[type=submit] {background-color:#111;color:#eee;border:#333 1px solid;cursor:pointer}
		input[type=submit]:hover, input[type=submit]:focus {background-color:#222}
</style>

<h1 id="levelAnnouncer" class="bangers">Game Over!</h1>

<div id="submitScore">
	<h1>Submit your Score:</h1>
	<input type="text" value="<?php echo $this->summoner['name']; ?>" readonly>
	<input type="text" value="<?php echo $this->score; ?>" readonly>
	<input type="submit" class="submit" case="submit" value="Submit Score"> 
	<input type="submit" class="submit" case="play" value="Play Again"> 
</div>

<script type="text/javascript">
	$("#panel").fadeOut("fast");
	$("title").html("Game Over &nbsp;::&nbsp; <?php echo $this->summoner['name']; ?> &nbsp;::&nbsp; Destroy The Nexus &nbsp;::&nbsp; Champion Master");

	$("#content").imagesLoaded().then(function(){
		$("#levelAnnouncer").fadeIn(function(){
			setTimeout(function(){
				$("#levelAnnouncer").fadeOut(function(){
					$("#submitScore").fadeIn();
				});
			}, 2000);
		});
	});

	$(".submit").click(function(){
		if($(this).attr('case') == "submit"){
			$("#submitScore").fadeOut(function(){
				consultar('submitScore');	
			});
		} else {
			$("#submitScore").fadeOut(function(){
				window.location = "<?php echo $this->conf->http_url; ?>destroythenexus";
			});
		}
	});
</script>
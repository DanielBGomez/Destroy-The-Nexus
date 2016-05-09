<div id="event" class="no-select">
	<style>
		#event h1 {margin-bottom: 20px;}
		input, select, option {color:#333;width:390px;font-size: 18px;padding:15px;margin:0px auto 10px;display:block;box-sizing:border-box;border-radius: 5px;border:#aaa 1px solid;box-shadow:0px 0px 5px 1px #444}
		input[type=submit] {background-color:#111;color:#eee;border:#333 1px solid;cursor:pointer}
		input[type=submit]:hover, input[type=submit]:focus {background-color:#222}
		#error {width:370px; padding: 15px 10px;background-color: rgba(160,40,40,0.8);margin:0px auto;font-size:14px;color:#ccc;line-height: 1.6 }
	</style>

	<div id="summoner">
		<h1>Enter your Summoner Name:</h1>
		<input type="text" name="summoner" placeholder="Summoner Name" value="<?php if(isset($_COOKIE['summoner'])){ echo json_decode($_COOKIE['summoner'],true)['name']; } ?>">
		<select name="region">
		<?php
			foreach ($this->conf->regionstr as $val => $region) {
				echo '<option value="'.$val.'"';
				if(isset($_COOKIE['region']) && $_COOKIE['region'] == $val){
					echo 'selected';
				} 
				echo '>'.$region.'</option>';
			}
		?>
		</select>
		<input type="submit" value="Submit">
		<?php if(isset($_COOKIE['error'])){ ?>
			<div id="error"><?php echo $_COOKIE['error']; ?></div>
		<?php } ?>
	</div>
</div>

<script>
	$("title").html("Enter your Summoner Name &nbsp;::&nbsp; Destroy The Nexus &nbsp;::&nbsp; Champion Master");
	$("#content").imagesLoaded().then(function(){
		$("#event").fadeIn(1000);
	});

	$("input[type=\'submit\']").click(function(){
		$("#error").hide();
		console.log("submit");
		if( $("[name=\'summoner\']").val() != "" && $("[name=\'region\']").val() != "" ){
			window.location = "<?php echo $this->conf->http_url; ?>destroythenexus/?case=verifyUser&summoner="+$("[name=\'summoner\']").val()+"&region="+$("[name=\'region\']").val();
		} else {
			$("#error").text("You must insert a Summoner name and select a Region!").show();
		}
	});

	$(document).keyup(function(e){
		$("#error").hide();
		if( e.keyCode == 13 ){
			if( $("[name=\'summoner\']").val() != "" && $("[name=\'region\']").val() != "" ){
				window.location = "<?php echo $this->conf->http_url; ?>destroythenexus/?case=verifyUser&summoner="+$("[name=\'summoner\']").val()+"&region="+$("[name=\'region\']").val();
			} else {
				$("#error").text("You must insert a Summoner name and select a Region!").show();
			}
		}
	});
</script>

</div>
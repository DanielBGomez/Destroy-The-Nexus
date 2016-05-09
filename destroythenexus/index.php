<style type="text/css">
	.loading {background-image: url('<?php echo $core->conf->http_url ?>src/img/gifs/kata_dancing.gif');background-repeat: no-repeat;background-position: center;background-size: 130px; height: 100vh}

	#page {padding-top: 0px}
	#content {width: 90%;margin: 0px 5%;display: flex;min-height: 100vh}

	#panel {position:fixed; bottom: 0px; display: none;background-color: rgba(20,20,20,0.8);width: 100%;left:0px;box-shadow: 0px 0px 10px 4px #333;z-index: 10000}
	#panel h2 {font-size: 14px;margin: 5px 10px;text-align:left; font-family: monospace;font-weight: 400;display: inline-block;}
	#panel h2 span {font-size: 16px}
	#panel h2 img {float:left;height:30px;width: 30px;margin:-5px 5px 0px}
	#lives img {float:right !important; height: 13px !important;width: auto !important;margin:3px  0px 0px!important;}
	#display {width: 100%;margin: auto;padding: 96px 0px 105px }
	#game {display: none;}	
</style>

<div id="content" class="loading">

	<div id="panel" class="no-select">
		<h2 id="level">Level: <span>#</span></h2> -
		<h2 id="score">Score: <span>#</span></h2> -
		<h2 id="lives">Lives:&nbsp;<span>#</span></h2> -
		<h2 id="time">Time: <span>##</span></h2>
		<div id="championsPoints"></div>
	</div>

	<div id="display"></div>


	<?php
	?>
	<script type="text/javascript">
		$(window).load(function(){
			$("#content").removeClass("loading");
		});

		var xhttp;
		if (window.XMLHttpRequest) {
			xhttp = new XMLHttpRequest();
		} else {
			xhttp = new ActiveXObject("Microsoft.XMLHTTP");
		}
			xhttp.onreadystatechange = function() {
			if (xhttp.readyState == 4) {
				if(xhttp.status == 200){
					$data = xhttp.responseText;
					$("#content").find("#display").html($data).imagesLoaded().then(function(){
						$("#content").removeClass("loading");
					});
				} else {
					$("#content").removeClass("loading").find("#display").addClass("error").html('<h1>Something went wrong!<br>Please try again later :C</h1>');
				}
			}
		}

		consultar("<?php if(isset($_GET['game'])){ echo 'game'; } elseif(isset($_GET['scores'])) { echo 'scores'; } else { echo 'index'; } ?>");
 
		$("#content").addClass("loading").find("#display").html('');
		function consultar($params){
			xhttp.abort();
			$("#event").remove();
			$("#content").addClass("loading");
			xhttp.open("POST", "<?php echo $core->conf->http_url.'destroythenexus/?case='; ?>"+$params, true);
			xhttp.send();
		}
	</script>

</div>
<?php
	if(!isset($this->scores) ){
		http_response_code(400);exit;
	}
?>
<script type="text/javascript" src="<?php echo $this->conf->http_url; ?>src/js/js-cookie.js"></script>
<style type="text/css">
	#scores {overflow: auto;display: none}
	#scores h1 {margin-bottom: 10px;}
	table {width: 500px;margin: 0px auto;border: #888 1px solid;}
	table tr:nth-of-type(even) {background-color: #555}
	table tr:nth-of-type(odd) {background-color: #444}
	table td {padding: 5px 10px;border:#888 1px solid; color: #ddd;}
	.head {background-color: #222 !important}
	.head td {color:#fff !important;}
	.name {text-align: left;text-transform: capitalize;}
</style>

<div id="scores">
	<h1>Top 10 Scores</h1>
	<table cellspacing="0">
		<tr class="head"><td width="30px">#</td><td>Summoner Name</td><td width="60px">Level</td><td width="130px">Highest Score</td></tr>
		<?php $i = 1; foreach ($this->scores as $name => $scoreData) { 
			if($i <= 10){ ?>
				<tr><td><?php echo $i++; ?></td><td class="name"><?php echo $scoreData['name']; ?></td><td class="level"><?php echo $scoreData['level']; ?></td><td class="score"><?php echo $scoreData['score']; ?></td></tr>
		<?php } 
			if( isset($this->summoner) && $scoreData['name'] == $this->summoner['name'] ){
				$position = $i-1;
			}
		} ?>
	</table>
	<?php if(isset($this->summoner)) { 
		$scoreData = $this->scores[$this->summoner['name']]; ?>
		<h1 style="font-size: 20px;margin: 20px auto 5px">Your Highest Score</h1>
		<table cellspacing="0">
			<tr><td width="30px"><?php if(isset($position)){ echo $position; } ?></td><td class="name"><?php echo $scoreData['name']; ?></td><td class="level" width="60px"><?php echo $scoreData['level']; ?></td><td class="score" width="130px"><?php echo $scoreData['score']; ?></td></tr>
		</table>
	<?php } ?>
</div>

<script type="text/javascript">
	$("#panel").fadeOut("fast");
	$("title").html("Top Scores &nbsp;::&nbsp; Destroy The Nexus &nbsp;::&nbsp; Champion Master");

	$("#content").imagesLoaded().then(function(){
		$("#scores").fadeIn("slow");
	});
</script>
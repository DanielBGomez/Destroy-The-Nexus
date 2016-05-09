<style type="text/css">
	#content {display: inline-block;width: 80%;max-width: 1200px}
	.separador {margin: 50px auto;}
	.separador p {text-align: justify;font-size: 18px;margin-bottom: 10px;}
	.separador h1 {margin-bottom: 10px;text-transform: capitalize;font-size: 40px}

	.button {padding: 15px 30px;border-radius: 15px; border: #555 1px solid;box-shadow: 0px 0px 5px 2px #333;font-variant: small-caps;font-weight: 700; margin: 5px 10px;display: inline-block;}
	.button:hover, .button:focus {opacity: 0.7; box-shadow: 0px 0px 5px 5px #333}
	.random {background-color: rgba(120,80,80,0.8)}
	.select {background-color: rgba(120,120,150,0.8)}
	.best {background-color: rgba(120,150,120,0.8)}
</style>

<div id="content">
	
	<div class="separador">
		<h1>Destroy the Nexus</h1>
		<p>
			In Destroy the Nexus, you must be fast and quick by pressing the correct spells to destroy the nexus that has a life equal to the champion mastery points of your best champions (Top 10).
			The Spells will approach to their key, and the closer they are when you press the key the more damage you deal to the nexus, but if you miss, you'll receive damage.<br><br>
			You think you can reach the maximum level using your best champion?<br><br>
		</p>
		<a href="<?php echo $core->conf->http_url; ?>destroythenexus" class="button random">Destroy the Nexus</a>
		<a href="<?php echo $core->conf->http_url; ?>destroythenexus?scores" class="button best">Top 10 Scores</a>
	</div>
</div>
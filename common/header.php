<?php
if(!isset($core)){
	header('Location: ../');
}
?>
<!DOCTYPE html>
<html>
	<head>
		<title><?php if(!empty($core->title)){ echo $core->title. ' &nbsp; :: &nbsp;'; }?>Champion Master</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"> 
		<link rel="icon" href="<?php echo $core->conf->http_url; ?>src/img/favicon-min.png">
		<script type="text/javascript" src="<?php echo $core->conf->http_url;?>src/js/jquery.js"></script>
		<script type="text/javascript" src="<?php echo $core->conf->http_url;?>src/js/imagesLoaded.js"></script>
		<script type="text/javascript" src="<?php echo $core->conf->http_url;?>src/js/cssBrowserSelector.js"></script>
		<script type="text/javascript">
			$(window).load(function(){
				if($(window).width() < 400){
					$scale = ($(window).width() / 460);

					$("meta[name='viewport']").attr("content", "width=400, initial-scale="+$scale+", maximum-scale="+$scale+", user-scalable=no");
				}
			});
		</script>
		<link href='https://fonts.googleapis.com/css?family=Bangers|Josefin+Sans:400,700' rel='stylesheet' type='text/css'>
		<style type="text/css">
			* {margin: 0;padding: 0;text-align: center;font-family: 'Josefin Sans', sans-serif;color: white;}
			body {background-color: #666;background-image: url('<?php echo $core->conf->http_url; ?>src/img/back2-min.png');background-size: 100%;background-size:cover;background-attachment: fixed;background-position: center top}
			a {text-decoration: none;}
			small {opacity: 0.7}
			li {list-style:none;}

			.left {float:left !important;}
			.right {float: right !important;}
			.bangers {font-family: 'Bangers' !important; font-weight: 400 !important	}
			.wfix {width: 100%;display:inline-block;max-width: 1200px}
			.no-select {-webkit-user-select: none;-moz-user-select: none;-khtml-user-select: none;-ms-user-select:none;user-drag: none; -webkit-user-drag: none;}

			#page {width: 100%;min-height: 100vh;padding-top: 46px}

			#header {width: 100%;background-color: #222;display: inline-block;box-shadow: 0px 0px 10px 4px #333;position: fixed;left:0;top:0;z-index: 10000}
			#menu {height: 25px;position: absolute;padding: 20px 15px;display: none;cursor: pointer;z-index: 10;float:left;}
			#navigation {float: left}
			#navigation h3 {text-transform: uppercase;font-size:30px;float:left;margin:7px 25px 7px 20px;font-family: 'Bangers',sans-serif;font-weight: 400}
			#navigation h3:hover, #navigation h3:focus {opacity: 0.7}
			#navigation li {float:left;padding: 13px 20px;font-weight: 700;text-shadow:1px 1px 2px #ccc;}
			#navigation li:hover, #navigation li:focus {background-color: rgba(255,255,255,0.2);}
			#navigation a {padding: 13px 20px;}
			#content {width:100%;}
			@media screen and (max-width: 1000px){
				#navigation{width: 100%;}
				a[menu] {display: inline-block;}
				#menu {display: block}
				#navigation h3{margin:15px auto !important; text-align: center; }
				#navigation li{width: 100%; padding:13px 0px; background-color: rgba(255,255,255,0.1);display: none}
				#navigation a{padding:0px;}
			}
		</style>
	</head>

	<body>
		<div id="page">
			<header id="header">
				<ul id="navigation">
					<img src="<?php echo $core->conf->http_url; ?>/src/img/menu-icon-min.png" id="menu">
					<a menu href="<?php echo $core->conf->http_url; ?>"><h3>Champion Master</h3></a>
					<a href="<?php echo $core->conf->http_url; ?>"><li>Home</li></a>
					<a href="<?php echo $core->conf->http_url; ?>destroythenexus"><li>Destroy the Nexus</li></a>
					<a href="https://github.com/DanielBGomez/Destroy-The-Nexus" target="_blank"><li>About</li></a>
				</ul>
				<script type="text/javascript">
					$("#menu").click(function(){
						$("#navigation li").stop().slideToggle(200);
					});
				</script>
			</header>
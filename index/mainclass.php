<?php
	class index extends Core {
		function __construct(){
			$this->conf = new Configuration();

		}

		function __destruct(){ unset($this); }
	}

	$core = new Index();
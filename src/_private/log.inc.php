<?php
	if (! (defined("CCTRB") && (CCTRB == "D870DCC7-2666-46C5-A1ED-CDA9E7D6F71B"))) {
		http_response_code(404);
		exit;
	}

	function log_add($ligne) {
		if (("127.0.0.1" == $_SERVER["SERVER_ADDR"]) || ("::1" == $_SERVER["SERVER_ADDR"])) {
			require_once(__DIR__."/configlog-dev.inc.php");
			if (CCTRBLogOnOff) 
				file_put_contents(CCTRBLogFile,@file_get_contents(CCTRBLogFile).$ligne."\n");
		}
	}
	log_add("_GET= ".var_export($_GET,true));
	log_add("_POST= ".var_export($_POST,true));
	log_add("_SERVER= ".var_export($_SERVER,true));

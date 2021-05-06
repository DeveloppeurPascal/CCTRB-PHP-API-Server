<?php
	if (! (defined("CCTRB") && (CCTRB == "D870DCC7-2666-46C5-A1ED-CDA9E7D6F71B"))) {
		http_response_code(404);
		exit;
	}

	define("CCTRBLogOnOff",true); // true or false
	define("CCTRBLogFile","C:\wamp64\www\log.txt");

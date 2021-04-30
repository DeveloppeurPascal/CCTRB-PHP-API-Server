<?php
	if (! (defined("CCTRB") && (CCTRB == "D870DCC7-2666-46C5-A1ED-CDA9E7D6F71B"))) {
		http_response_code(404);
		exit;
	}

	require_once(dirname(__FILE__)."/config.inc.php");

	try {
		$db = new PDO("mysql:dbname=".DB_NAME.";host=".DB_HOST.";charset=UTF8", DB_USER, DB_PWD);
	}
	catch (PDOException $e) {
		http_response_code(503);
		exit;
	}

	if (! is_object($db)) {
		http_response_code(503);
		exit;
	}

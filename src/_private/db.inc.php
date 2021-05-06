<?php
	if (! (defined("CCTRB") && (CCTRB == "D870DCC7-2666-46C5-A1ED-CDA9E7D6F71B"))) {
		http_response_code(404);
		exit;
	}

	if (("127.0.0.1" == $_SERVER["SERVER_ADDR"]) || ("::1" == $_SERVER["SERVER_ADDR"])) {
		require_once(__DIR__."/config-dev.inc.php");
	}
	else {
		require_once(__DIR__."/config-prod.inc.php");
	}

	try {
		$db = new PDO("mysql:dbname=".DB_NAME.";host=".DB_HOST.";charset=UTF8", DB_USER, DB_PWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
	}
	catch (PDOException $e) {
		http_response_code(503);
		exit;
	}

	if (! is_object($db)) {
		http_response_code(503);
		exit;
	}

<?php
	// http://localhost:8080/cliadd

	header('Content-Type: application/json; charset=utf8');
	header('Access-Control-Allow-Origin: https://cctrb.fr/');

	define("CCTRB","D870DCC7-2666-46C5-A1ED-CDA9E7D6F71B");

	// require_once(__DIR__."/../_private/log.inc.php");

	if (isset($_GET["v"])) {
		$checksum = $_GET["v"];
		// log_add($checksum);
		require_once(__DIR__."/../_private/ChecksumVerif.inc.php");
		if (! checkVerifChecksum($checksum, CCTRBPrivateKey)) {
			http_response_code(400);
			exit;
		}
	}
	else {
		http_response_code(500);
		exit;
	}

	require_once(__DIR__."/../_private/db.inc.php");

	require_once(__DIR__."/../_private/tools.inc.php");
	$KPriv = getKeyPrivPub();
	$KPub = getKeyPrivPub();
	
	$qry = $db->prepare("insert into clients (ClePublique,ClePrivee) values (:kpub,:kpriv)");
	if (FALSE === $qry->execute(array(":kpub"=>$KPub,":kpriv"=>$KPriv))) {
		http_response_code(400);
		exit;
	}
	else {
		$result = new stdClass();
		$result->id = $db->lastInsertId()*1;
		$result->kpriv = $KPriv;
		$result->kpub = $KPub;

		$json = json_encode($result);
		if (FALSE !== $json) {
			http_response_code(200);
			print($json);
		}
		else {
			http_response_code(500);
			exit;
		}
	}

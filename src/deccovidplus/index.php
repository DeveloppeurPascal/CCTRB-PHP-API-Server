<?php
	// http://localhost:8080/decovidplus
	
	header('Content-Type: application/json; charset=utf8');
	header('Access-Control-Allow-Origin: https://cctrb.fr/');

	define("CCTRB","D870DCC7-2666-46C5-A1ED-CDA9E7D6F71B");

	// require_once(__DIR__."/../_private/log.inc.php");

	if (isset($_GET["c"])) {
		try {
			$IDClient = $_GET["c"]*1;
		}
		catch (Exception $e) {
			http_response_code(400);
			exit;
		}
		if ($IDClient < 1) {
			http_response_code(400);
			exit;
		}
	}
	else {
		http_response_code(400);
		exit;
	}

	require_once(__DIR__."/../_private/db.inc.php");

	$qry = $db->prepare("select ClePrivee from clients where IDClient=:idc");
	$qry->execute(array(":idc"=>$IDClient));
	if (FALSE === ($rec = $qry->fetch(PDO::FETCH_ASSOC))) {
		http_response_code(500);
		exit;
	}
	else {
		$ClePrivee = $rec["ClePrivee"];
	}

	if (isset($_GET["v"])) {
		$checksum = $_GET["v"];
		require_once(__DIR__."/../_private/ChecksumVerif.inc.php");
		if (! checkVerifChecksum($checksum, $ClePrivee, $IDClient)) {
			http_response_code(400);
			exit;
		}
	}
	else {
		http_response_code(500);
		exit;
	}

	$qry = $db->prepare("insert into declarations (IDClient, DateHeureDeclarationPositif) values (:idc,:dhe)");
	if (FALSE === $qry->execute(array(":idc"=>$IDClient,":dhe"=>date("YmdHi")))) {
		http_response_code(400);
		exit;
	}
	else {
		// TODO : impacter les "cas contact" sur la base de données d'historiques
		http_response_code(200);
	}

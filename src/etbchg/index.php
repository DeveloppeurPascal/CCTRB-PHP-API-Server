<?php
	header('Content-Type: application/json; charset=utf8');
	header('Access-Control-Allow-Origin: https://cctrb.fr/');

	define("CCTRB","D870DCC7-2666-46C5-A1ED-CDA9E7D6F71B");

	if (isset($_POST["i"])) {
		try {
			$IDEtablissement = $_POST["i"]*1;
		}
		catch (Exception $e) {
			http_response_code(400);
			exit;
		}
		if ($IDEtablissement < 1) {
			http_response_code(400);
			exit;
		}
	}
	else {
		http_response_code(400);
		exit;
	}

	if (isset($_POST["l"])) {
		$raison_sociale = $_POST["l"];
		if (empty($raison_sociale)) {
			http_response_code(400);
			exit;
		}
	}
	else {
		http_response_code(400);
		exit;
	}

	if (isset($_POST["t"])) {
		try {
			$IDTypeEtablissement = $_POST["t"]*1;
		}
		catch (Exception $e) {
			http_response_code(400);
			exit;
		}
		if ($IDTypeEtablissement < 1) {
			http_response_code(400);
			exit;
		}
	}
	else {
		http_response_code(400);
		exit;
	}

	if (isset($_POST["v1"])) {
		$checksum = $_POST["v1"];
		require_once(__DIR__."/../_private/ChecksumVerif.inc.php");
		if (! checkVerifChecksum($checksum, CCTRBPrivateKey, $IDEtablissement, $raison_sociale, $IDTypeEtablissement)) {
			http_response_code(400);
			exit;
		}
	}
	else {
		http_response_code(500);
		exit;
	}

	require_once(__DIR__."/../_private/db.inc.php");

	$qry = $db->prepare("select ClePrivee from etablissements where IDEtablissement=:idetb");
	$qry->execute(array(":idetb"=>$IDEtablissement));
	if (FALSE === ($rec = $qry->fetch(PDO::FETCH_ASSOC))) {
		http_response_code(500);
		exit;
	}
	else {
		$ClePrivee = $rec["ClePrivee"];
	}

	if (isset($_POST["v2"])) {
		$checksum = $_POST["v2"];
		// require_once(__DIR__."/../_private/ChecksumVerif.inc.php");
		if (! checkVerifChecksum($checksum, $ClePrivee, $IDEtablissement, $raison_sociale, $IDTypeEtablissement)) {
			http_response_code(400);
			exit;
		}
	}
	else {
		http_response_code(500);
		exit;
	}

	$qry = $db->prepare("select IDTypeEtablissement from typesetablissements where IDTypeEtablissement=:idetb");
	$qry->execute(array(":idetb"=>$IDTypeEtablissement));
	if (FALSE === ($rec = $qry->fetch(PDO::FETCH_ASSOC))) {
		http_response_code(500);
		exit;
	}
	
	$qry = $db->prepare("update etablissements set RaisonSociale=:rs,IDTypeEtablissement=:idte where IDEtablissement=:ide");
	if (FALSE === $qry->execute(array(":rs"=>$raison_sociale,":idte"=>$IDTypeEtablissement,":ide"=>$IDEtablissement))) {
		http_response_code(400);
		exit;
	}
	else {
		http_response_code(200);
	}

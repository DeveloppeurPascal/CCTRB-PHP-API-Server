<?php
	// http://localhost:8080/clioutetb
	
	header('Content-Type: application/json; charset=utf8');
	header('Access-Control-Allow-Origin: https://cctrb.fr/');

	define("CCTRB","D870DCC7-2666-46C5-A1ED-CDA9E7D6F71B");

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

	if (isset($_GET["i"])) {
		try {
			$IDEtablissement = $_GET["i"]*1;
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
		if (! checkVerifChecksum($checksum, $ClePrivee, $IDClient, $IDEtablissement)) {
			http_response_code(400);
			exit;
		}
	}
	else {
		http_response_code(500);
		exit;
	}

	$qry = $db->prepare("select IDEtablissement from etablissements where IDEtablissement=:idetb");
	$qry->execute(array(":idetb"=>$IDEtablissement));
	if (FALSE === ($rec = $qry->fetch(PDO::FETCH_ASSOC))) {
		http_response_code(500);
		exit;
	}

	$qry = $db->prepare("select DateHeureEntree from historiques where IDClient=:idc and IDEtablissement=:ide and ((DateHeureSortie is null) or (DateHeureSortie=\"000000000000\")) order by DateHeureEntree desc");
	$qry->execute(array(":idc"=>$IDClient,":ide"=>$IDEtablissement));
	if (FALSE === ($rec = $qry->fetch(PDO::FETCH_ASSOC))) {
		http_response_code(301);
		exit;
	}
	else {
		$DateHeureEntree = $rec["DateHeureEntree"];
	}

	$qry = $db->prepare("update historiques set DateHeureSortie=:dhs where IDClient=:idc and IDEtablissement=:ide and DateHeureEntree=:dhe");
	if (FALSE === $qry->execute(array(":idc"=>$IDClient,":ide"=>$IDEtablissement,":dhe"=>$DateHeureEntree,":dhs"=>date("YmdHi")))) {
		http_response_code(400);
		exit;
	}
	else {
		http_response_code(200);
	}

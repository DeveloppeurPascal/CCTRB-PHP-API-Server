<?php
	header('Content-Type: application/json; charset=utf8');
	header('Access-Control-Allow-Origin: https://cctrb.fr/');

	define("CCTRB","D870DCC7-2666-46C5-A1ED-CDA9E7D6F71B");

	// require_once(__DIR__."/../_private/log.inc.php");

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

	$qry = $db->prepare("select ClePrivee from etablissements where IDEtablissement=:idetb");
	$qry->execute(array(":idetb"=>$IDEtablissement));
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
		if (! checkVerifChecksum($checksum, $ClePrivee, $IDEtablissement)) {
			http_response_code(400);
			exit;
		}
	}
	else {
		http_response_code(500);
		exit;
	}
	
	$result = array();

	$qry = $db->prepare("select DateHeureEntree, DateHeureSortie from historiques where IDEtablissement=:ide and CasContact=1 order by DateHeureEntree, DateHeureSortie");
	$qry->execute(array(":ide"=>$IDEtablissement));
	while (FALSE !== ($rec = $qry->fetch(PDO::FETCH_ASSOC))) {
		$MaLigne=new stdClass();
		$MaLigne->StartDate = $rec["DateHeureEntree"];
		$MaLigne->EndDate = $rec["DateHeureSortie"];
		$result[] = $MaLigne;
	}

	$json = json_encode($result);
	if (FALSE !== $json) {
		http_response_code(200);
		print($json);
	}
	else {
		http_response_code(500);
		exit;
	}

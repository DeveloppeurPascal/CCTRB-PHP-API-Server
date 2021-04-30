<?php
	header('Content-Type: application/json; charset=utf8');
	header('Access-Control-Allow-Origin: https://cctrb.fr/');

	define("CCTRB","D870DCC7-2666-46C5-A1ED-CDA9E7D6F71B");

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

	if (isset($_POST["v"])) {
		$checksum = $_POST["v"];
		require_once(dirname(__FILE__)."/../_private/ChecksumVerif.inc.php");
		if (! checkVerifChecksum($checksum, CCTRBPrivateKey, $raison_sociale, $IDTypeEtablissement)) {
			http_response_code(400);
			exit;
		}
	}
	else {
		http_response_code(500);
		exit;
	}

	require_once(dirname(__FILE__)."/../_private/db.inc.php");

	$qry = $db->prepare("select IDTypeEtablissement from typesetablissements where IDTypeEtablissement=:idetb");
	$qry->execute(array(":idetb"->$IDTypeEtablissement));
	if (FALSE === ($rec = $qry->fetch(PDO::FETCH_ASSOC))) {
		http_response_code(500);
		exit;
	}
	
	require_once(dirname(__FILE__)."/../_private/tools.inc.php");
	$KPriv = getKeyPrivPub();
	$KPub = getKeyPrivPub();
	
	$qry = $db->prepare("insert into etablissements (RaisonSociale,IDTypeEtablissement,ClePublique,ClePrivee) values (:rs,:idte,:kpub,:kpriv)");
	if (FALSE === $qry->execute(array(":rs"->$raison_sociale,":idte"->$IDTypeEtablissement,":kpub"->$KPub,":kpriv"->$KPriv))) {
		http_response_code(400);
		exit;
	}
	else {
		$result = new stdClass();
		$result->id = $db->lastInsertId();
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

<?php
	header('Content-Type: application/json; charset=utf8');
	header('Access-Control-Allow-Origin: https://cctrb.fr/');

	define("CCTRB","D870DCC7-2666-46C5-A1ED-CDA9E7D6F71B");

	// require_once(__DIR__."/../_private/log.inc.php");

	if (isset($_POST["l"])) {
		$raison_sociale = $_POST["l"];
		// log_add($raison_sociale);
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
			// log_add(gettype($IDTypeEtablissement));
			// log_add($IDTypeEtablissement);
			// log_add(gettype($IDTypeEtablissement));
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

// log_add("etape 1");
	if (isset($_POST["v"])) {
		$checksum = $_POST["v"];
		// log_add($checksum);
		require_once(__DIR__."/../_private/ChecksumVerif.inc.php");
		if (! checkVerifChecksum($checksum, CCTRBPrivateKey, $raison_sociale, $IDTypeEtablissement)) {
			http_response_code(400);
			exit;
		}
	}
	else {
		http_response_code(500);
		exit;
	}
// log_add("etape 2");
	require_once(__DIR__."/../_private/db.inc.php");

// log_add("etape 3");
	$qry = $db->prepare("select IDTypeEtablissement from typesetablissements where IDTypeEtablissement=:idetb");
	$qry->execute(array(":idetb"=>$IDTypeEtablissement));
	if (FALSE === ($rec = $qry->fetch(PDO::FETCH_ASSOC))) {
		http_response_code(500);
		exit;
	}
	
// log_add("etape 4");
	require_once(__DIR__."/../_private/tools.inc.php");
// log_add("etape 5");
	$KPriv = getKeyPrivPub();
	$KPub = getKeyPrivPub();
	
	$qry = $db->prepare("insert into etablissements (RaisonSociale,IDTypeEtablissement,ClePublique,ClePrivee) values (:rs,:idte,:kpub,:kpriv)");
	if (FALSE === $qry->execute(array(":rs"=>$raison_sociale,":idte"=>$IDTypeEtablissement,":kpub"=>$KPub,":kpriv"=>$KPriv))) {
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

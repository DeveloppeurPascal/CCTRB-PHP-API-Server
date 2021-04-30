<?php
	header('Content-Type: application/json; charset=utf8');
	header('Access-Control-Allow-Origin: https://cctrb.fr/');

	define("CCTRB","D870DCC7-2666-46C5-A1ED-CDA9E7D6F71B");
	
	require_once(dirname(__FILE__)."/../_private/db.inc.php");

	$result = array();

	$qry = $db->query("select * from typesetablissements order by libelle", PDO::FETCH_ASSOC);
	foreach ($qry as $rec) {
		$MaLigne=new stdClass();
		$MaLigne->id = $rec["IDTypeEtablissement"]*1;
		$MaLigne->label = $rec["libelle"];
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

<?php
// Infos sur https://trucs-de-developpeur-web.fr/p/_3002-calculer-et-verifier-un-checksum-pour-dialoguer-avec-l-exterieur.html

	if (! (defined("CCTRB") && (CCTRB == "D870DCC7-2666-46C5-A1ED-CDA9E7D6F71B"))) {
		http_response_code(404);
		exit;
	}

	// return a checksum value for "verif" URL param
	function getVerifChecksum($param, $key1="", $key2="", $key3="", $key4="", $key5="", $public=true)
	{
		$verif = "";
		if (is_array($param)) {
			$par = "";
			foreach($param as $value) {
				$par .= $value;
			}
			$verif = md5($par.$key1.$key2.$key3.$key4.$key5);
		}
		else {
			$verif = md5($param.$key1.$key2.$key3.$key4.$key5);
		}
		return ($public)?substr($verif,mt_rand(0,strlen($verif)-10),10):$verif;
	}
	
	// check a "verif" checksum value
	// return TRUE if ok, FALSE if not
	function checkVerifChecksum($verif, $param, $key1="", $key2="", $key3="", $key4="", $key5="")
	{
		if ((strlen($verif) < 1) && isset($_POST["verif"]))
		{
			$verif = $_POST["verif"];
		}
		if ((strlen($verif) < 1) && isset($_GET["verif"]))
		{
			$verif = $_GET["verif"];
		}
		return (false !== strpos(getVerifChecksum($param, $key1, $key2, $key3, $key4, $key5, false),$verif));
	}

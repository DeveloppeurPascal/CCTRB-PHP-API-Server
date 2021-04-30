<?php
	if (! (defined("CCTRB") && (CCTRB == "D870DCC7-2666-46C5-A1ED-CDA9E7D6F71B"))) {
		http_response_code(404);
		exit;
	}

	function CaractereAleatoire() {
		$nb = mt_rand(0, 10 + 26 + 26 - 1);
		if ($nb < 10) { // chiffres
			return chr(ord('0') + $nb);
		}
		else if ($nb < 10+26) { // lettres minuscules
			return chr(ord('a') + $nb - 10);
		}
		else if ($nb < 10+26+26) { // lettres majuscules
			return chr(ord('A') + $nb - 10 - 26);
		}
		else {
			return '0';
		}
	}

	function getKeyPrivPub($taille = 64) {
		$res = '';
		while ($taille-- > 0) {
			$res = $res.CaractereAleatoire();
		}
		return $res;
	}

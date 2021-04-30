<?php
	define("CCTRB","D870DCC7-2666-46C5-A1ED-CDA9E7D6F71B");
	
	require_once(dirname(__FILE__)."/_private/tools.inc.php");
	var_dump(getKeyPrivPub());
		
	require_once(dirname(__FILE__)."/_private/ChecksumVerif.inc.php");
	var_dump(checkVerifChecksum(getVerifChecksum('toto','titi','5454324dsfg2'),'toto','titi','5454324dsfg2'));
	var_dump(checkVerifChecksum(getVerifChecksum('toto','titi','5454324dsfg2'),'toto','titi','fgbffff'));

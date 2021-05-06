<?php
	if (("127.0.0.1" == $_SERVER["SERVER_ADDR"]) || ("::1" == $_SERVER["SERVER_ADDR"])) {
	}
	else {
		header("location: https://cctrb.fr");
	}
<?php

require("global_lib.php");


if (isset($_POST["cmd"])) {
	$cmd = $_POST["cmd"];
	if ($cmd == "set_lang") {
		$r = setLang();
		echo json_encode($r);
	}
}


?>
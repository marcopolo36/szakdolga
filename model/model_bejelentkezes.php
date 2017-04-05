<?php

	checkPermission('bejelentkezes');
	$errors = array();
        
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		if(! login($_POST["nev"],$_POST["jelszo"])) {
			$errors[] = "Sikertelen bejelentkezés";
		} else {
			print "<script type='text/javascript'>".
				  "window.location.href = 'index.php?site=kezdolap';".
				  "</script>";
		}
	}
?>
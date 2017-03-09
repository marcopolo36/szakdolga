<?php

checkPermission('bejelentkezes');

	$page_title = "Kezdőlap";
	$menu = getMenu();
	$page_main_title = "Bejelentkezés oldal!";
        $login_sikertelen = false;
        
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if(! login($_POST["nev"],$_POST["jelszo"])) {
                $login_sikertelen = true;
            } else {
                header('Location: index.php?site=kezdolap');
            }
        }
?>
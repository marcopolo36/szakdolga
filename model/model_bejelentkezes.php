<?php

checkPermission('bejelentkezes');

	$page_title = "Kezdőlap";
	$menu = getMenu();
	$page_main_title = "Bejelentkezés oldal!";
        $errors = array();
        
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if(! login($_POST["nev"],$_POST["jelszo"])) {
                $errors[] = "Sikertelen bejelentkezés";
            } else {
                header('Location: index.php?site=kezdolap');
            }
        }
?>
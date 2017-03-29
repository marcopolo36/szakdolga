<?php

checkPermission('kvizjatek');

	$page_title = "Kvízjáték";
	$menu = getMenu();
	$page_main_title = "Kvízjáték oldal!";
	$page_content = "";
        $errors = array();
        
        $db_iface = new MySQLDatabase();

        //header('Content-type: text/html; charset=iso-8859-2');

        //kvíz megjelenítés
	/*A $promotion_id azonosítójú kvízt jeleníti meg*/
	
	//ellenőrzés hogy létezik-e ez az azonosító + a cím lekérdezése
	$result = $db_iface->query('SELECT * FROM `{PREFIX}promocio` ORDER BY `id`;', array());
	$rows = ($result)?mysql_num_rows($result):0;
	if($rows == 0) {
		print 'Egy kvíz sem létezik.';
		return;
	}
?>

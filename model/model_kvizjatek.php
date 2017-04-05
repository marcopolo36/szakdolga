<?php

	checkPermission('kvizjatek');
	$errors = array();
	
	$db_iface = new MySQLDatabase();

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

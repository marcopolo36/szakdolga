<?php
//pelda.php
session_start();
include('kviz.php');
?>
<html>
	<head>
		<title>Példa quiz megjelenítõ oldal</title>
		<link rel="stylesheet" href="pelda.css"/>
	</head>
	<body><div class="quiz">
	<h1>EZ AZ OLDAL NEM BIZTONSÁGOS TÖRÖLD</H1>
	<B>Ez az oldal példa az adminisztrációs felület megjelenítésére, de azonnal töröld le, mivel amint látod, itt semmilyen jelszavas védelemmel nincs ellátva az admin. Az adminisztrációs panel beillesztéséhez használd az quiz_admin() függvényt a kviz.php importálása után. De ezt a függvényt mindig feltételes szerkezetben használd, és csak akkor hívd meg ha biztos hogy az oldal letöltõjének van joga az adminisztrációhoz.</b>
	<BR><?php quiz_admin(); ?></div>
	</body>
</html>
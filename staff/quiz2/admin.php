<?php
//pelda.php
session_start();
include('kviz.php');
?>
<html>
	<head>
		<title>P�lda quiz megjelen�t� oldal</title>
		<link rel="stylesheet" href="pelda.css"/>
	</head>
	<body><div class="quiz">
	<h1>EZ AZ OLDAL NEM BIZTONS�GOS T�R�LD</H1>
	<B>Ez az oldal p�lda az adminisztr�ci�s fel�let megjelen�t�s�re, de azonnal t�r�ld le, mivel amint l�tod, itt semmilyen jelszavas v�delemmel nincs ell�tva az admin. Az adminisztr�ci�s panel beilleszt�s�hez haszn�ld az quiz_admin() f�ggv�nyt a kviz.php import�l�sa ut�n. De ezt a f�ggv�nyt mindig felt�teles szerkezetben haszn�ld, �s csak akkor h�vd meg ha biztos hogy az oldal let�lt�j�nek van joga az adminisztr�ci�hoz.</b>
	<BR><?php quiz_admin(); ?></div>
	</body>
</html>
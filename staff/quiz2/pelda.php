<?php
//pelda.php
session_start();
include('kviz.php');
?>
<html>
	<head>
		<link rel="stylesheet" href="pelda.css" type="text/css"/>
		<title>P�lda quiz megjelen�t� oldal</title>
	</head>
	<body>
	<?php quiz((isset($_GET['quiz'])&&is_numeric($_GET['quiz']))?$_GET['quiz']:1); ?>
	</body>
</html>
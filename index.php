<?php
session_start();
if(! isset($_SESSION)) {
	//session_start();
}

// php üzenetek kikapcsolása biztonsági okból
//error_reporting(0);

// fontos, hogy milyen sorrendben includálunk

// segéd-függvénykönyvtárak betöltése
include("tool.php");// így za összes oldal tudja használni az áltlános segédfüggvéneket
include("mysqldatabase.php");//így az összes oldal tudja használni az adatbázist
include("authentication.php");//így az összes oldal tudja használni a függvényeit

// routing indítása
include("controller/controller.php");

?>
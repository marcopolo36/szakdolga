<?php

session_start();

// fontos, hogy milyen sorrendben includálunk

// segéd-függvénykönyvtárak betöltése
include("mysqldatabase.php");//így az összes oldal tudja használni az adatbázist
include("authentication.php");//így az összes oldal tudja használni a függvényeit

// routing indítása
include("controller/controller.php");

?>
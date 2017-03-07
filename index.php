<?php

session_start();

// fontos, hogy milyen sorrendben includálunk

// segéd-függvénykönyvtárak betöltése
include("mysqldatabase.php");
include("authentication.php");

// routing indítása
include("controller/controller.php");

?>
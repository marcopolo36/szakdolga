<?php

if (isset($_GET["site"]) && 
	$_GET["site"]== "kijelentkezes" && 
	hasPermission("kijelentkezes")
	){
	logOut();
	print "<script type='text/javascript'>".
		  "window.location.href = 'index.php?site=kezdolap';".
		  "</script>";
}
$menu = getMenu();
$page_title = "Engedély megtagadva";
if(isset($_GET["site"]) && isset($menu[$_GET["site"]])) {
	$page_title = $_GET["site"];
}
include("view/header.php");
if(isset($_GET["site"])){
	if($_GET["site"]=="kezdolap") {            
		include("model/model_kezdolap.php");
		include("view/view_kezdolap.php");
	} elseif ($_GET["site"]=="regisztracio"){
		include("model/model_regisztracio.php");
		include("view/view_regisztracio.php");	
	} elseif ($_GET["site"]=="uzenetkuldes"){
		include("model/model_uzenetkuldes.php");
		include("view/view_uzenetkuldes.php");
        } elseif ($_GET["site"]=="kvizjatek"){
		include("model/model_kvizjatek.php");
		include("view/view_kvizjatek.php");
        } elseif ($_GET["site"]=="kviz"){
		include("model/model_kviz.php");
		include("view/view_kviz.php");
        } elseif ($_GET["site"]=="kapcsolat"){
		include("model/model_kapcsolat.php");
		include("view/view_kapcsolat.php");
        } elseif ($_GET["site"]=="bejelentkezes"){
		include("model/model_bejelentkezes.php");
		include("view/view_bejelentkezes.php");
        } elseif ($_GET["site"]=="admin"){
		include("model/model_admin.php");
		include("view/view_admin.php");
        } elseif ($_GET["site"]=="titkos"){
		include("model/model_titkos.php");
		include("view/view_titkos.php");
        } elseif ($_GET["site"]=="feltetelek"){
		include("model/model_feltetelek.php");
		include("view/view_feltetelek.php");
	} else {
		include("model/model_kezdolap.php");
		include("view/view_kezdolap.php");			
	}
} else {
        include("model/model_kezdolap.php");
        include("view/view_kezdolap.php");
}
showErrors($errors);

?>
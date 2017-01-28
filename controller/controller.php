<?php

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
        } elseif ($_GET["site"]=="kapcsolat"){
		include("model/model_kapcsolat.php");
		include("view/view_kapcsolat.php");
        } elseif ($_GET["site"]=="bejelentkezes"){
		include("model/model_bejelentkezes.php");
		include("view/view_bejelentkezes.php");
        } elseif ($_GET["site"]=="kijelentkezes"){
		include("model/model_kijelentkezes.php");
		include("view/view_kijelentkezes.php");
	} else {
		include("model/model_kezdolap.php");
		include("view/view_kezdolap.php");		
	}
} else {
		include("model/model_kezdolap.php");
		include("view/view_kezdolap.php");	
	
}

?>
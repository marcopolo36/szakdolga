<?php

if(isset($_GET["site"])){
	
	if($_GET["site"]=="kezdolap") {
		include("model/model_kezdolap.php");
		include("view/view_kezdolap.php");
	} elseif ($_GET["site"]=="videok"){
		include("model/model_videok.php");
		include("view/view_videok.php");	
	} elseif ($_GET["site"]=="select"){
		include("model/model_select.php");
		include("view/view_select.php");		
	} else {
		include("model/model_kezdolap.php");
		include("view/view_kezdolap.php");		
	}
	
} else {
	
		include("model/model_kezdolap.php");
		include("view/view_kezdolap.php");	
	
}

?>
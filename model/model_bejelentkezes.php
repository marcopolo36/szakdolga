<?php
        include './tools.php';

        function authentication ($nev, $jelszo){
            
            // SQL utasítás
            $sql = "SELECT * FROM felhasznalo WHERE '".$nev."'=nev AND  '".$jelszo."'=jelszo";

            // SQL utasítás végrehajtása
            
            $eredmeny = lekerdez($sql);
                  
            if ($eredmeny->num_rows == 1)
            {
                $_SESSION['felhasznalo_nev']=$nev;
            }else{
                echo "Bejelentkezés sikertelen!";
            }
            
        }

	$page_title = "Kezdőlap";
	$menu = array(
		"kezdolap"=>"Kezdőlap", 
		"regisztracio"=>"Regisztráció",
		"uzenetkuldes"=>"Üzenetküldés",
		"kvizjatek"=>"Kvízjáték",
                "kapcsolat"=>"Kapcsolat",
                "bejelentkezes"=>"Bejelentkezés"
		);
	$page_main_title = "Bejelentkezés oldal!";

        $nev = $jelszo = "";
        
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $nev = test_input($_POST["nev"]);
            $jelszo = test_input($_POST["jelszo"]);
            authentication ($nev,$jelszo);
        }
?>
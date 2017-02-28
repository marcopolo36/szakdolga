<?php
        include ('mysqldatabase.php');

	$page_title = "Kvíz";
	$menu = array(
		"kezdolap"=>"Kezdőlap", 
		"regisztracio"=>"Regisztráció",
		"uzenetkuldes"=>"Üzenetküldés",
		"kvizjatek"=>"Kvízjáték",
                "kapcsolat"=>"Kapcsolat",
                "bejelentkezes"=>"Bejelentkezés",
                "admin"=>"Adminisztráció"
		);
	$page_main_title = "Titkos üzeneted kvízjátéka!";
	$page_content = "";

        $uzenet_id;
        if(isset($_GET["uzenet_id"])) {
            $uzenet_id = $_GET["uzenet_id"];
        } else {
            print "Hiba: hibás üzenet id";
        }
        
        $db_iface = new MySQLDatabase();

        if(! isset($_POST['valasz']) && ! isset($_SESSION["allapot"])) { // 1. állapot: form kitöltése (első megjelenés)
            $_SESSION["allapot"] = "kerdes_form";
            $result = $db_iface->query(
                'SELECT * FROM `{PREFIX}uzenet` WHERE `{PREFIX}uzenet`.`id`={ID};',
                array('ID'=>$uzenet_id));
            $rows = ($result)?mysql_num_rows($result):0;
            if($rows == 0) {
                    print 'Az id='.$uzenet_id.' kvíz nem létezik.';
                    return;
            }
            $row = mysql_fetch_assoc($result); //üzenet adattábla egy sorát adja vissza, betöltöm egy asszociációs tömmbe utána kulcs és érték párokat rendelek hozzá
            $_SESSION["lekerdezes"] = array(
		"id"=>$row["id"], 
		"kuldo_felhasznalo_id"=>$row["kuldo_felhasznalo_id"],
		"emailcim"=>$row["emailcim"],
		"keresztnev_kuldo"=>$row["keresztnev_kuldo"],
                "kerdes"=>$row["kerdes"],
                "valasz_1"=>$row["valasz_1"],
                "valasz_2"=>$row["valasz_2"],
                "valasz_3"=>$row["valasz_3"],
                "helyesvalasz_sorszam"=>$row["helyesvalasz_sorszam"],
                "t_uzenet"=>$row["t_uzenet"],
                "kep_id"=>$row["kep_id"]
		);            
        } elseif ($_SESSION["allapot"] == "kerdes_form"){ // 1. állapot küldés után
            if(strcmp($_POST['valasz'], $_SESSION["lekerdezes"]["helyesvalasz_sorszam"]) == 0) { //string összehasonlítás
                $_SESSION["allapot"] = "keresztnev_form"; // 2. állapot-ba kerül
                $_SESSION["nev_probalkozas"] = 0;
            }
        } elseif ($_SESSION["allapot"] == "keresztnev_form"){ // 2. állapot
            ++$_SESSION["nev_probalkozas"];
            if(strcmp($_POST['keresztnev'], $_SESSION["lekerdezes"]["keresztnev_kuldo"]) == 0) { //string összehasonlítás
                $_SESSION["allapot"] = "email_form"; // 3/a. állapotba kerül
            } else if($_SESSION["nev_probalkozas"] >= 3) {
                $_SESSION["allapot"] = "sikertelen"; // 3/b. állapotba kerül
            }
        }
?>
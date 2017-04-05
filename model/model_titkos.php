<?php //Ez az oldal nem jelenik meg a menüben, mert az emailben található linkről landolnak itt. A GET szuperglobális tömbböl kiszedi a titkos üzenet azonosító számát.Ha nem 
// ha nem találja hibát dob. A viewban kitölthetjük a kvízt. A végén a regisztrációd oldal linkje megjelenik.
 
		checkPermission('titkos');

        $errors = array();

        $uzenet_id;
        if(isset($_GET["uzenet_id"])) {
            $uzenet_id = $_GET["uzenet_id"];
        } else {
            print "Hiba: hibás üzenet id";
        }
        
        $db_iface = new MySQLDatabase();

        if(! isset($_POST['valasz']) && ! isset($_POST["keresztnev"])) { // 1. állapot: form kitöltése (első megjelenés)
            $_SESSION["allapot"] = "kerdes_form";
            $result = $db_iface->query(
                'SELECT * FROM `{PREFIX}uzenet` WHERE `{PREFIX}uzenet`.`id`={ID} AND `megtekintett`=0;',
                array('ID'=>$uzenet_id));
            $rows = ($result)?mysql_num_rows($result):0;
            if($rows == 0) {
                    die('A kvíz nem létezik vagy már ki lett töltve');
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
                save();
            } else if($_SESSION["nev_probalkozas"] >= 3) {
                $_SESSION["allapot"] = "sikertelen"; // 3/b. állapotba kerül
                save();
            }
        }
        
        function save() {
            global $db_iface;
            $result = $db_iface->query(
                'UPDATE `{PREFIX}uzenet` SET megtekintett = 1 WHERE `{PREFIX}uzenet`.`id`={ID};',
                array('ID'=>$_SESSION["lekerdezes"]["id"]));
            if(! $result) {
                    print 'A titkos üzenet megtekintettségének mentése sikertelen.';
                    return;
            }
        }
?>
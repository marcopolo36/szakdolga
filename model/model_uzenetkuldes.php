<?php
    include('Mail.php');
    include('Mail/mime.php');
    include('mysqldatabase.php');

    $db_iface = new MySQLDatabase();
    
    $test_user_id = 1;
    
    function saveFormToSession(){
        global $test_user_id;
        $_SESSION['uzenet']=array('KULDO_FELHASZNALO_ID'=>$test_user_id,
                                  'EMAILCIM'=>$_POST['to'],
                                  'KERESZTNEV_KULDO'=>$_POST['firstname'],
                                  'KERDES'=>$_POST['kerdes'],
                                  'VALASZ_1'=>$_POST['valasz_0'],
                                  'VALASZ_2'=>$_POST['valasz_1'],
                                  'VALASZ_3'=>$_POST['valasz_2'], 
                                  'HELYESVALASZ_SORSZAM'=>$_POST['helyes'],
                                  'T_UZENET'=>$_POST['t_uzenet'],
                                  'KEP_ID'=>$_POST['picture']);
    }
    
    function alapertekkel_feltolt_form (){
        global $test_user_id;
        $_SESSION['uzenet']=array('KULDO_FELHASZNALO_ID'=>$test_user_id,
                                  'EMAILCIM'=>"",
                                  'KERESZTNEV_KULDO'=>"",
                                  'KERDES'=>"",
                                  'VALASZ_1'=>"",
                                  'VALASZ_2'=>"",
                                  'VALASZ_3'=>"",
                                  'HELYESVALASZ_SORSZAM'=>"-1", //azt, jelenti, hogy nem írt be numerikus értéket 
                                  'T_UZENET'=>"",
                                  'KEP_ID'=>"-1");
    }
    
    function betolt($cel, $tomorites, $tipus, $magassagX, $szelessegY) {
        if($tipus == IMAGETYPE_JPEG) {
            $kep = imagecreatefromjpeg($cel);
        } elseif($tipus == IMAGETYPE_GIF) {
            $kep = imagecreatefromgif($cel);
        } elseif($tipus == IMAGETYPE_PNG) {
            $kep = imagecreatefrompng($cel);
        } else {
            return false;
        }

        //rairjuk
        kepreir($kep, $cel, $tomorites, $tipus, $magassagX, $szelessegY);
    }

    function kepreir($kep, $kepnev, $tomorites, $tipus, $magassagX, $szelessegY) {
        $betu_meret = 30;
        $betu_szin = imagecolorallocate ($kep, 0,0,0);
        $betu_tipus = "images/BAUHS93.TTF";
        // 1, 30 koordinatak x y hol helyezkedjen el
        imagefttext($kep, $betu_meret, 0, $magassagX, $szelessegY, $betu_szin, $betu_tipus, $_POST["szoveg"]);
        //mentjuk
        ment($kep, $kepnev, $tomorites, $tipus);
    }

    function ment($kep, $kepnev, $tomorites, $tipus) {
        if($tipus == IMAGETYPE_JPEG) {
            imagejpeg($kep, 'images/temp.jpg', $tomorites);
        } elseif($tipus == IMAGETYPE_GIF) {
            imagegif($kep, 'images/temp.gif');
        } elseif($tipus == IMAGETYPE_PNG) {
            imagepng($kep,  'images/temp.png');
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
    $page_main_title = "Üzenetküldés oldal!";
    $page_content = "";
    $valaszok = array();
    $errors = array();
    if (isset($_POST['action']) && $_POST['action'] == 'new_message') { //új kérdést viszünk be
        saveFormToSession();
        $siker = false;
        if(isset($_POST['valaszok']) && !empty($_POST['valaszok']) && is_numeric($_POST['valaszok'])) { //érkezzenek postból válaszok és legalább 2 legyen!
            for($i=0;$i<3;$i++) {
                if(isset($_POST['valasz_'.$i]) && !empty($_POST['valasz_'.$i])) {
                    $valaszok[$i] = $_POST['valasz_'.$i];
                }
            }      
            if (count($valaszok) != 3) {
                $errors[] = 'Nem töltötted ki az összes választ';
            } elseif(!isset($_POST['kerdes']) || $errors[] = 'Üresen hagytad a kérdés címét' && empty($_POST['kerdes'])) {
                $errors[] = 'Üresen hagytad a kérdés címét';
            } elseif(!isset($_POST['helyes']) || !is_numeric($_POST['helyes']) || !isset($valaszok[$_POST['helyes']])) {
                $errors[] = 'Nem jelölted ki a helyes választ';
            } else {
                
                $success = $db_iface->query('INSERT INTO `{PREFIX}uzenet` (`kuldo_felhasznalo_id`,`emailcim`,`keresztnev_kuldo`,`kerdes`,`valasz_1`,`valasz_2`,`valasz_3`,`helyesvalasz_sorszam`,`t_uzenet`,`kep_id`) ' .
                    'VALUES ( \'{KULDO_FELHASZNALO_ID}\', \'{EMAILCIM}\',\'{KERESZTNEV_KULDO}\',\'{KERDES}\',\'{VALASZ_1}\',\'{VALASZ_2}\',\'{VALASZ_3}\',\'{HELYESVALASZ_SORSZAM}\',\'{T_UZENET}\',\'{KEP_ID}\');',
                    $_SESSION['uzenet']); // a kérdés tábla több mezőjébe is új értékeket szúr be a postokból
                if(!$success) {
                    $errors[] = $db_iface->report();
                }
                
             //Jelenleg  hibát dob a hiányzó kep_id miatt és nem írja be az adatbázisba!!               

            }
        }
        
    }else{
        alapertekkel_feltolt_form ();
    }
    
    if (isset($_POST["to"])) {
        $host = "ssl://smtp.gmail.com";
        $port = "465"; //465 or 8465 can also be used
        $username = "divenyi.officenet@gmail.com";
        $password = "marcopolo36";
        $from = "divenyi.officenet@gmail.com";
        $to = $_POST["to"];   
        $subject = "Titkos üzeneted érkezett";
        $text = $_POST["szoveg"];
        $html = "<html><body>".$_POST["szoveg"]."</body></html>";
        $crlf = "\n";
        $headers = array (
            "From" => $from,
            "To" => $to,
            "Subject" => $subject);
        $mime = new Mail_mime($crlf);
        $mime->setTXTBody($text);
        $mime->setHTMLBody($html);
        $forras;
        // űrlapról érkező fájl becsatolása kezdete
        switch ($_POST["picture"]) {
            case "1":
                $forras="images/nevnap.jpg";
                break;
            case "2":
                $forras="images/szulinap.jpg";
                break;
            case "3":
                $forras="images/valentinnap.jpg";
                break;
        }
       	// űrlapról érkező fájl ideiglenes helye
        $cel="sent_images/temporary_image.jpg";   // ide másoljuk (aktuális mappába, eredeti néven)
        copy($forras,$cel);       // mozgatás elvégzése      
 
        $tomorites = 100;
        $kep_info = getimagesize($cel);
        $magassagX = (int)($kep_info[0]/2);  
        $szelessegY = (int)($kep_info[1]/2);  
        $tipus = $kep_info[2];  

        betolt($cel, $tomorites, $tipus, $magassagX, $szelessegY);
        
        //email küldés
        $mime->addAttachment("images/temp.jpg");  // hozzáfűzés a levélhez
        // űrlapról érkező fájl becsatolása vége
        $body = $mime->get();
        //die($body);
        $headers = $mime->headers($headers);
        $smtp = Mail::factory("smtp", array (
            "host" => $host,
            "port" => $port,
            "auth" => true,
            "username" => $username,
            "password" => $password));
        $mail = $smtp->send($to, $headers, $body);
        if (PEAR::isError($mail)) {
                print("<p>Hibaüzenet: ".$mail->getMessage()."</p>");
        } else {
                print("<p>Levélküldés sikerült.</p>");
        }
        @unlink("./".$_FILES["csatolt"]["name"]); // a feltöltött és a már - remélhetőleg - elküldött fájl törlése
    }      
?>
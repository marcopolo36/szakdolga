<?php
    include('Mail.php');
    include('Mail/mime.php');
    include('mysqldatabase.php');

    $db_iface = new MySQLDatabase(); 
    $test_user_id = 1; //Autentikációt meg kell írni
    $kep_id;
    $uzenet_id;
    
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
                                  'SZOVEG'=>$_POST['szoveg'],
                                  'PICTURE_NUM'=>$_POST['picture']);
    }
    
    function alapertekkel_feltolt_form () // a form a visszalépésnék kitöltött mezőkkel jelenjen meg
    {   
        global $test_user_id;
        $_SESSION['uzenet']=array('KULDO_FELHASZNALO_ID'=>$test_user_id, //kulcshoz => érték hozzárendelés
                                  'EMAILCIM'=>"",
                                  'KERESZTNEV_KULDO'=>"",
                                  'KERDES'=>"",
                                  'VALASZ_1'=>"",
                                  'VALASZ_2'=>"",
                                  'VALASZ_3'=>"",
                                  'HELYESVALASZ_SORSZAM'=>"-1", //azt, jelenti, hogy nem írt be numerikus értéket 
                                  'T_UZENET'=>"",
                                  'SZOVEG'=>"",
                                  'PICTURE_NUM'=>"-1");
    }
   
   function make_picture() //kép gyártása és elmentése
   {
       global $picture_dir;
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
        $cel = $picture_dir . "temporary_image.jpg";   // ide másoljuk (aktuális mappába, eredeti néven)
        copy($forras,$cel);       // mozgatás elvégzése      

        $tomorites = 100;
        $kep_info = getimagesize($cel);
        $magassagX = (int)($kep_info[0]/2);  
        $szelessegY = (int)($kep_info[1]/2);   
        $kep = imagecreatefromjpeg($cel);
        
        kepreir($kep, $tomorites, $magassagX, $szelessegY);
   }
    
   function emailt_kuld() // email küldése képpel
   {
       global $picture_dir;
       global $kep_id;
       global $uzenet_id;
        if (isset($_POST["to"])) {
            $host = "ssl://smtp.gmail.com";
            $port = "465"; //465 or 8465 can also be used
            $username = "divenyi.officenet@gmail.com";
            $password = "marcopolo36";
            $from = "divenyi.officenet@gmail.com";
            $to = $_POST["to"];   
            $subject = "Titkos üzeneted érkezett";
            $url = "http://localhost/szakdolga/index.php?site=titkos&uzenet_id=" . $uzenet_id ;
            $text = $_POST["szoveg"] ."\n Titkos üzenetedet itt olvashatod el: ". $url ;
            $html = "<html><body>".$_POST["szoveg"]."<br><a href='" . $url ."'>Titkos üzenetedet itt olvashatod el</a></body></html>";
            $crlf = "\n";
            $headers = array (
                "From" => $from,
                "To" => $to,
                "Subject" => $subject);
            $mime = new Mail_mime($crlf);
            $mime->setTXTBody($text);
            $mime->setHTMLBody($html);            
            //email küldés
            
            $picture_path = $picture_dir . $kep_id . ".jpg";
            $mime->addAttachment($picture_path);  // hozzáfűzés a levélhez
            
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
   }
    
   function ment_adatbazisba(){ // adatbázisba menti a képet
        global $test_user_id;
        global $kep_id;
        global $db_iface;
        $success = $db_iface->query("INSERT INTO `{PREFIX}kep` () VALUES ();", array());
        if(!$success) {
            $errors[] = $db_iface->report();
        }
        $kep_id = $db_iface->last_inserted_id();//hozzáférek az objektum függvényéhez $db_iface->last_inserted_id()
    }
    
    function kepreir($kep, $tomorites, $magassagX, $szelessegY) { // a képre égeti a beírt szöveget
        global $picture_dir;
        global $kep_id;
        $betu_meret = 30;
        $betu_szin = imagecolorallocate ($kep, 0,0,0);
        $betu_tipus = "images/BAUHS93.TTF";
        // 1, 30 koordinatak x y hol helyezkedjen el
        imagefttext($kep, $betu_meret, 0, $magassagX, $szelessegY, $betu_szin, $betu_tipus, $_POST["szoveg"]);
        //mentjuk
        ment_adatbazisba();
        $file_name = $picture_dir . $kep_id . ".jpg";
        imagejpeg($kep, $file_name, $tomorites);
    }  

    function kviz_mentes()
    {   
        global $test_user_id;
        global $kep_id;
        global $db_iface;
        global $uzenet_id;
        $success = false;
        saveFormToSession();
        if(isset($_POST['valaszok']) && !empty($_POST['valaszok']) && is_numeric($_POST['valaszok'])) { //érkezzenek postból válaszok és legalább 2 legyen!
            for($i=0;$i<3;$i++) {
                if(isset($_POST['valasz_'.$i]) && !empty($_POST['valasz_'.$i]))
                {
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
               $query_string = 'INSERT INTO `{PREFIX}uzenet` ' . 
                               '(`kuldo_felhasznalo_id`,`emailcim`,`keresztnev_kuldo`,`kerdes`,`valasz_1`,`valasz_2`,`valasz_3`,`helyesvalasz_sorszam`,`t_uzenet`,`kep_id`) ' .
                               'VALUES ( \'{KULDO_FELHASZNALO_ID}\', \'{EMAILCIM}\',\'{KERESZTNEV_KULDO}\',\'{KERDES}\',\'{VALASZ_1}\',\'{VALASZ_2}\',\'{VALASZ_3}\',\'{HELYESVALASZ_SORSZAM}\',\'{T_UZENET}\',\'{KEP_ID}\');';
               $params_array =  array ( 'KULDO_FELHASZNALO_ID'=>$test_user_id,
                                        'EMAILCIM'=>$_POST['to'],
                                        'KERESZTNEV_KULDO'=>$_POST['firstname'],
                                        'KERDES'=>$_POST['kerdes'],
                                        'VALASZ_1'=>$_POST['valasz_0'],
                                        'VALASZ_2'=>$_POST['valasz_1'],
                                        'VALASZ_3'=>$_POST['valasz_2'], 
                                        'HELYESVALASZ_SORSZAM'=>$_POST['helyes'],
                                        'T_UZENET'=>$_POST['t_uzenet'],
                                        'KEP_ID'=>$kep_id);
               $result = $db_iface->query($query_string, $params_array);
               $uzenet_id = $db_iface->last_inserted_id();
                // a kérdés tábla több mezőjébe is új értékeket szúr be a postokból
                if(!$result) {
                    $errors[] = $db_iface->report();
                    $success = false;
                } else {
                    $success = true;
                }
            }
        }
        return $success;
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
    $picture_dir = "sent_images/";

    if(isset($_POST["picture"])) {
        make_picture();
        $success = kviz_mentes();
        if($success) {
            emailt_kuld();
        }
    } else {
        alapertekkel_feltolt_form ();
    }    
       
?>
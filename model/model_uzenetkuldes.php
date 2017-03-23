<?php

checkPermission('uzenetkuldes');

    include('Mail.php');
    include('Mail/mime.php');
  
    $page_main_title = "Üzenetküldés oldal!";
    $page_content = "";
    $page_title = "Üzenetküldés";
    $menu = getMenu();
    $errors = array();

    $db_iface = new MySQLDatabase();
    $kep_id;
    $uzenet_id;
    
    $valaszok = array();
    $picture_dir = "sent_images/";
    
    function saveFormToSession(){
        $_SESSION['uzenet']=array('EMAILCIM'=>$_POST['to'], //kulcshoz => érték hozzárendelés
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
    
    function alapertekkel_feltolt_form () // a form a visszalépésnél kitöltött mezőkkel jelenjen meg
    {   
        $_SESSION['uzenet']=array('EMAILCIM'=>"", //kulcshoz => érték hozzárendelés
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
            $host = "ssl://smtp.gmail.com"; //a tárhely szolgáltató értékeire átírni
            $port = "465"; //a tárhely szolgáltató értékeire átírni
            $username = "divenyi.officenet@gmail.com"; //a tárhely szolgáltató értékeire átírni
            $password = "marcopolo36"; //a tárhely szolgáltató értékeire átírni
            $from = "divenyi.officenet@gmail.com"; //a tárhely szolgáltató értékeire átírni
            $to = $_POST["to"];   
            $subject = "Titkos üzeneted érkezett";
            $url = "http://localhost/szakdolga/index.php?site=titkos&uzenet_id=" . $uzenet_id ;
            $text = $_POST["szoveg"] ."\n Titkos üzenetedet itt olvashatod el: ". $url ;
            $html = "<html lang='hu'><body>".$_POST["szoveg"]."<br/><a href='" . $url ."'> ***Titkos üzenetedet itt olvashatod el</a></body></html>";
            $crlf = "\n";
            $headers = array (
		"Content-Type" => "text/html; charset=UTF-8",
                "From" => $from,
                "To" => $to,
                "Subject" => $subject);
            $mime = new Mail_mime($crlf);
            $mime->setTXTBody($text);
            $mime->setHTMLBody($html);
			$mime_params = array(
			  'text_encoding' => '7bit',
			  'text_charset'  => 'UTF-8',
			  'html_charset'  => 'UTF-8',
			  'head_charset'  => 'UTF-8'
			);			
            //email küldés
            
            $picture_path = $picture_dir . $kep_id . ".jpg";
            $mime->addAttachment($picture_path);  // hozzáfűzés a levélhez
            
            // űrlapról érkező fájl becsatolása vége
            $body = $mime->get($mime_params);
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
                    $errors[] = "Hibaüzenet: ".$mail->getMessage();
            }
            @unlink("./".$_FILES["csatolt"]["name"]); // a feltöltött és a már - remélhetőleg - elküldött fájl törlése
        }  
   }
    
   function ment_adatbazisba(){ // adatbázisba menti a képet
        global $kep_id;
        global $db_iface;
        $success = $db_iface->query("INSERT INTO `{PREFIX}kep` () VALUES ();", array());
        if(!$success) {
            $errors[] = "A kép mentése nem sikerült!";
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
        global $kep_id;
        global $db_iface;
        global $uzenet_id;
        $success = false;
        saveFormToSession();
        if(isset($_POST['valaszok']) && !empty($_POST['valaszok']) && is_numeric($_POST['valaszok'])) { //érkezzenek postból válaszok és legalább 2 legyen! 
            if(empty($errors)) {
               $query_string = 'INSERT INTO `{PREFIX}uzenet` ' . 
                               '(`kuldo_felhasznalo_id`,`emailcim`,`keresztnev_kuldo`,`kerdes`,`valasz_1`,`valasz_2`,`valasz_3`,`helyesvalasz_sorszam`,`t_uzenet`,`kep_id`) ' .
                               'VALUES ( \'{KULDO_FELHASZNALO_ID}\', \'{EMAILCIM}\',\'{KERESZTNEV_KULDO}\',\'{KERDES}\',\'{VALASZ_1}\',\'{VALASZ_2}\',\'{VALASZ_3}\',\'{HELYESVALASZ_SORSZAM}\',\'{T_UZENET}\',\'{KEP_ID}\');';
               $params_array =  array ( 'KULDO_FELHASZNALO_ID'=>$_SESSION["user"]['id'],
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
                    $errors[] = "A titkos üzenet mentése sikertelen!";
                    $success = false;
                } else {
                    $success = true;
                }
            }
        }
        return $success;
    }
    
    function formValidation() {
        global $valaszok, $errors;
        if(empty($_POST['kerdes'])) {
            $errors[] = 'Üresen hagytad a kérdés címét.';
        }
        if(! isset($_POST["helyes"])) {
            $errors[] = "Nincs kiválasztva helyes válasz.";
        }
        for($i=0;$i<3;$i++) {
            if(isset($_POST['valasz_'.$i]) && !empty($_POST['valasz_'.$i])) {
                $valaszok[$i] = $_POST['valasz_'.$i];
            }
        }      
        if (count($valaszok) != 3) {
            $errors[] = 'Nem töltötted ki az összes választ.';
        }
        if(empty($_POST['t_uzenet'])) {
            $errors[] = 'Üresen hagytad a titkos üzenetet.';
        }
        if(empty($_POST['firstname'])) {
            $errors[] = 'Üresen hagytad a keresztnév mezőt.';
        }
        if(empty($_POST['to'])) {
            $errors[] = 'Üresen hagytad a címzett mezőt.';
        }
        if(empty($_POST['szoveg'])) {
            $errors[] = 'Üresen hagytad az üdvözlő üzenet mezőt.';
        }
        if(! isset($_POST["picture"])) {
            $errors[] = "Nincs kiválasztva kép.";
        }
    }

    if(isset($_POST["kerdes"])) {
        formValidation();
        if(empty($errors)) {
            make_picture();
        }
        $success = false;
        if(empty($errors)) {
            $success = kviz_mentes();
        }
        if($success && empty($errors)) {
            emailt_kuld();
        }
    } else {
        alapertekkel_feltolt_form ();
    }
       
?>
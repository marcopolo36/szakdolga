<?php
    include('Mail.php');
    include('Mail/mime.php');
    
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
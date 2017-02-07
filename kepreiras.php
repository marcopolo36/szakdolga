<?php
  $forras=$_FILES["kep"]["tmp_name"];
  $kepnev = $_FILES["kep"]["name"];
  move_uploaded_file($forras,"./".$kepnev);
  $tomorites = 100;
   
  $kep_info = getimagesize($kepnev);
  $magassagX = (int)($kep_info[0]/2);  
  $szelessegY = (int)($kep_info[1]/2);  
  $tipus = $kep_info[2];  
   
   betolt($kepnev, $tomorites, $tipus, $magassagX, $szelessegY);
  //betoltjuk
  function betolt($kepnev, $tomorites, $tipus, $magassagX, $szelessegY) {
    if($tipus == IMAGETYPE_JPEG) {
    $kep = imagecreatefromjpeg($kepnev);
    } elseif($tipus == IMAGETYPE_GIF) {
    $kep = imagecreatefromgif($kepnev);
    } elseif($tipus == IMAGETYPE_PNG) {
    $kep = imagecreatefrompng($kepnev);
    } else {
        return false;
    }
     
    //rairjuk
    kepreir($kep, $kepnev, $tomorites, $tipus, $magassagX, $szelessegY);
  }
   
  function kepreir($kep, $kepnev, $tomorites, $tipus, $magassagX, $szelessegY) {
     $betu_meret = 30;
     $betu_szin = imagecolorallocate ($kep, 0,0,0);
     $betu_tipus = "bauhs93.ttf";
// 1, 30 koordinatak x y hol helyezkedjen el
   imagefttext($kep, $betu_meret, 0, $magassagX, $szelessegY, $betu_szin, $betu_tipus, $_POST["szoveg"]);
   //mentjuk
   ment($kep, $kepnev, $tomorites, $tipus);
  }
   
  function ment($kep, $kepnev, $tomorites, $tipus) {
    if($tipus == IMAGETYPE_JPEG) {
    imagejpeg($kep, 'uj_'.$kepnev, $tomorites);
    } elseif($tipus == IMAGETYPE_GIF) {
    imagegif($kep, 'uj_'.$kepnev);
    } elseif($tipus == IMAGETYPE_PNG) {
    imagepng($kep, 'uj_'.$kepnev);
    }
  }
  ?>
  
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>
<p>A megadott szöveg: <?php print($_POST["szoveg"]); ?></p>
<p>Az eredeti kép:<br />
  <img src="<?php print($kepnev); ?>"  /> </p>
<p>Együtt:<br />
  <img src="<?php print("uj_".$kepnev); ?>"  /></p>
</body>
</html>
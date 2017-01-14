<?php
  $tomorites = 100;
  
  if ($handle = opendir('.')) {
    while (false !== ($kepnev = readdir($handle))) {
       $kep_info = getimagesize($kepnev);
	  $magassagX = 10;  
	  $szelessegY = ($kep_info[1]-26);  
	  $tipus = $kep_info[2];
	   betolt($kepnev, $tomorites, $tipus, $magassagX, $szelessegY);
    }
    closedir($handle);
}
   
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
     $betu_meret = 18;
     $betu_szin = imagecolorallocate ($kep, 0,0,255);
     $betu_tipus = "bauhs93.ttf";
// 1, 30 koordinatak x y hol helyezkedjen el
   imagefttext($kep, $betu_meret, 0, $magassagX, $szelessegY, $betu_szin, $betu_tipus, "Mancika designs");
   //mentjuk
   ment($kep, $kepnev, $tomorites, $tipus);
  }
   
  function ment($kep, $kepnev, $tomorites, $tipus) {
    if($tipus == IMAGETYPE_JPEG) {
    imagejpeg($kep, 'manc_'.$kepnev, $tomorites);
    } elseif($tipus == IMAGETYPE_GIF) {
    imagegif($kep, 'manc_'.$kepnev);
    } elseif($tipus == IMAGETYPE_PNG) {
    imagepng($kep, 'manc_'.$kepnev);
    }
  }
  ?>

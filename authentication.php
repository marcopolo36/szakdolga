<?php

if(! isset($_SESSION["user"]) || ! isset($_SESSION["user"]['szerepkor']) || ! isset($_SESSION["user"]['nev'])) {
    setDefault();
}

$adminPermissions = array(
                    "kezdolap"=>"Kezdőlap", 
                    "regisztracio"=>"Regisztráció",
                    "uzenetkuldes"=>"Üzenetküldés",
                    "kvizjatek"=>"Kvízjáték",
                    "kviz"=>"Kvíz",
                    "kapcsolat"=>"Kapcsolat",
                    "feltetelek"=>"Feltételek",
                    "kijelentkezes"=>"Kijelentkezés",
                    "admin"=>"Adminisztráció"
               );

$userPermissions = array(
                    "kezdolap"=>"Kezdőlap", 
                    "uzenetkuldes"=>"Üzenetküldés",
                    "kvizjatek"=>"Kvízjáték",
                    "kviz"=>"Kvíz",
                    "kapcsolat"=>"Kapcsolat",
                    "feltetelek"=>"Feltételek",
                    "kijelentkezes"=>"Kijelentkezés"
                );

$anonymusPermissions = array(
                    "kezdolap"=>"Kezdőlap", 
                    "regisztracio"=>"Regisztráció",
                    "kapcsolat"=>"Kapcsolat",
                    "feltetelek"=>"Feltételek",
                    "bejelentkezes"=>"Bejelentkezés"
                );

function logOut()
{
    setDefault();
}

function setDefault() { //default értékekkel tölti fel a sessiont
    $_SESSION["user"] = array();
    $_SESSION["user"]['szerepkor'] = "Anonymus";
    $_SESSION["user"]['nev'] = "";
    $_SESSION["user"]['id'] = -1;
}

 function login ($nev, $jelszo){
    $db_iface = new MySQLDatabase();
    // SQL utasítás
    $nev = $db_iface->test_input($nev);
    $jelszo = $db_iface->test_input($jelszo);
    $query_string = "SELECT felhasznalo.id as user_id, szerepkor.nev as szerepkor_nev " .
                    "FROM felhasznalo JOIN szerepkor ON felhasznalo.szerepkor_id = szerepkor.id " . 
                    "WHERE felhasznalo.nev='{NEV}' AND jelszo='{JELSZO}'"; 
    $params = array('NEV'=>$nev,'JELSZO'=>$jelszo);
    $result = $db_iface->query($query_string,$params);
    $row_count = ($result)?mysql_num_rows($result):0;

    $_SESSION["user"] = array();
    if ($row_count == 1)
    {   
        $row = mysql_fetch_assoc($result); //kvíz adattábla egy sorát adja vissza
        $_SESSION["user"]['szerepkor'] = $row['szerepkor_nev'];
        $_SESSION["user"]['nev'] = $nev;
        $_SESSION["user"]['id'] = $row['user_id'];
        return true;
    } elseif($row_count == 0) {
        return false;
    } else {
        die("Belső hiba: felhasználó duplikáció. " . $db_iface->report());
        return false;
    }
}

// Leírás: elkészíti az aktuális felhasználó szerepkörének megfeleltett menüpontok tömbjét és visszaadja azt.
// Paraméterek:
//  (- x: string - a szakállas bácsi neve))
//  (- y: integer - tárolja a bácsi szakállának a hosszát)
// visszatérési érték: menupontok: array
function getMenu()
{   
    global $adminPermissions, $userPermissions, $anonymusPermissions;

    $permissions;
    switch ($_SESSION["user"]['szerepkor']) {
        case 'Admin':
            $permissions = $adminPermissions; break;
        case 'Felhasználó':
            $permissions = $userPermissions; break;
        case 'Anonymus':
            $permissions = $anonymusPermissions; break;
    }
    
    // szűrés
    unset($permissions["kviz"]);
    
    return $permissions;
}

function checkPermission($page)
{   
   global $adminPermissions, $userPermissions, $anonymusPermissions;
   
   $noPermission = "Nincs engedélyed a(z) " . $page . " oldalra belépni!";
   switch ($_SESSION["user"]['szerepkor']) {
        case 'Admin':
            if(array_key_exists($page,$adminPermissions) == false && $page != "titkos")
            {
                die($noPermission);
            }
            break;
        case 'Felhasználó':
            if(array_key_exists($page,$userPermissions) == false && $page != "titkos")
            {
                die($noPermission);
            }
            break;
        case 'Anonymus':
            if(array_key_exists($page,$anonymusPermissions) == false && $page != "titkos")
            {
                die($noPermission);
            }
            break;
    }
}

?>

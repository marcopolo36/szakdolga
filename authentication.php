<?php
if(! isset($_SESSION["user"]) || ! isset($_SESSION["user"]['szerepkor']) || ! isset($_SESSION["user"]['nev'])) {
    $_SESSION["user"] = array();
    $_SESSION["user"]['szerepkor'] = "Anonymus";
    $_SESSION["user"]['nev'] = "";
}

 function login ($nev, $jelszo){
    $db_iface = new MySQLDatabase();
    // SQL utasítás
    $nev = $db_iface->test_input($nev);
    $jelszo = $db_iface->test_input($jelszo);
    $query_string = "SELECT szerepkor.nev as szerepkor_nev " .
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
        return true;
    }else{
        print $db_iface->report();
        return false;
    }
}

function getMenu()
{
    switch ($_SESSION["user"]['szerepkor']) {
        case 'Admin':
            return array(
                    "kezdolap"=>"Kezdőlap", 
                    "regisztracio"=>"Regisztráció",
                    "uzenetkuldes"=>"Üzenetküldés",
                    "kvizjatek"=>"Kvízjáték",
                    "kapcsolat"=>"Kapcsolat",
                    "bejelentkezes"=>"Bejelentkezés",
                    "kijelentkezes"=>"Kijelentkezés",
                    "admin"=>"Adminisztráció"
               ); 
            break;
        case 'Felhasználó':
            return array(
                    "kezdolap"=>"Kezdőlap", 
                    "regisztracio"=>"Regisztráció",
                    "uzenetkuldes"=>"Üzenetküldés",
                    "kvizjatek"=>"Kvízjáték",
                    "kapcsolat"=>"Kapcsolat",
                    "bejelentkezes"=>"Bejelentkezés",
                    "kijelentkezes"=>"Kijelentkezés"
                ); 
            break;
        case 'Anonymus':
            return array(
                    "kezdolap"=>"Kezdőlap", 
                    "regisztracio"=>"Regisztráció",
                    "kapcsolat"=>"Kapcsolat",
                    "bejelentkezes"=>"Bejelentkezés"
                ); 
    }
}
?>

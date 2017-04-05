<?php

checkPermission('admin');
$errors = array();

//admin funkció
function remove_quiz($promocio_id) { //egy megadott id-jú kvízt töröl
	global $db_iface, $errors;
        $result = $db_iface->query('SELECT * FROM `{PREFIX}kerdes` WHERE `promocio_id`={ID};',array('ID'=>$promocio_id));//lekérdezi a megadott promoció id-hoz tartozó kérdéseket
        if(! $result) {
            $errors[] = "A törlendő kvízhez tartozó kérdések betöltése során hiba lépett fel.";// a hibát kiírja
            return;
        }
        $success = true;
        while($kerdes = mysql_fetch_assoc($result)) {
            $success = remove_question($kerdes['id']);
            if(! $success) {
                $errors[] = "Nem sikerült törölni a kérdést és a kérdéshez tartozó válaszokat.";
                return;
            }
        }

        $result = $db_iface->query('DELETE FROM `{PREFIX}promocio` WHERE `id`={ID};',array('ID'=>$promocio_id));//törli a kvízt
        if(! $result) {
            print $db_iface->report();
            $errors[] = "A kvíz törlése sikertelen.";
        }
}

function remove_question($kerdes_id) { //egy megadott id-jú kérdést töröl
	global $db_iface, $errors;
        
        $result = $db_iface->query('SELECT * FROM `{PREFIX}valasz` WHERE `kerdes_id`={ID};', array('ID'=>$kerdes_id));
        if(! $result) {
            $errors[] = "A törlendő kérdéshez tartozó válaszok lekérdezése során hiba lépett fel.";
        }
        $success = true;
        while($valasz = mysql_fetch_assoc($result)) {
            if(! remove_answer($valasz["id"])) {
                $success = false;
            }
        }
        if($success && ! $db_iface->query('DELETE FROM `{PREFIX}kerdes` WHERE `id`={ID};',array('ID'=>$kerdes_id))) {// majd a kérdést, amihez a válszok tartoztak 
            $errors[] = "A kérdés törlése sikertelen.";
            $success = false;
        }
        
        return $success;
}

function remove_answer($valasz_id) { // egy megadott id-jú választ töröl
	global $db_iface, $errors;
	$success = $db_iface->query('DELETE FROM `{PREFIX}valasz` WHERE `id`={ID};',array('ID'=>$valasz_id));
	if(!$success) {
            $errors[] = "A válasz törlése sikertelen.";
        }
        
        return $success;
}

function search($value) { //kulcsot keres a postolt értékekben
	$keys = array_keys($_POST);
	$keys_selected = array();
	foreach($keys as $key) {
		if(substr($_POST[$key],0,strlen($value)) == $value)
			$keys_selected[] = $key;
	}
	return $keys_selected;
}

//innen kezdődik a kód
$db_iface = new MySQLDatabase(); //MINDEN MODELBE KELL!
if(isset($_POST['action'])) {
        if($_POST['action'] == 'remove') { //kvíz törlés esete
                remove_quiz($_POST['del']);
        } elseif($_POST['action'] == 'show_quiz') {   //kvíz megjelenítés esete
                $id;
                if(!isset($_POST['quiz_id']) || empty($_POST['quiz_id']) || !is_numeric($_POST['quiz_id'])) { //validálás, megfelelő lesz-e a kvíz_id értéke
                        $id = -1;
                } else {
                        $id = (int)$_POST['quiz_id'];
                }
                $result = $db_iface->query('SELECT * FROM `{PREFIX}kerdes` WHERE `promocio_id`={ID};',array('ID'=>$id));
                $kerdesek = array(); 
                while($sor = mysql_fetch_assoc($result)) { //mejelenítjük a kvízt, amit törölni is tudunk
                        $valaszok_szama = $db_iface->num_rows('SELECT * FROM `{PREFIX}valasz` WHERE `kerdes_id`={ID}',array('ID'=>$sor['id']));//biztonsági megoldás, pl MySQL injection ellen
                        $kerdesek[] = array("id" => $sor['id'], 
                                            "kerdes" => $sor['szoveg'],
                                            "valaszok_szama" => $valaszok_szama);//létrehozzuk az asszociatívg tömb matrixot (3 oszloppal), sorokkal indexeljük, értékei az SQL lekérdezés lesz
                }
        } elseif($_POST['action'] == 'create') { //új kvíz készítés
                $success = false;
                if(isset($_POST['title']) && !empty($_POST['title'])) {
                    $success = $db_iface->query('INSERT INTO `{PREFIX}promocio` (`id`, `nev`, `leiras`, `datum`) VALUES (NULL, \'{TITLE}\', \'{LEIRAS}\', \'{DATUM}\');',array('TITLE'=>$_POST['title'], 'LEIRAS'=>$_POST['leiras'], 'DATUM'=>$_POST['datum'])); // a \ mindig a következő karakterre vonatkozik
                }
                if($success !== false) {
                    $quiz = $db_iface->last_inserted_id(); //az adatbázisba beszúr egy új id-t
                } /*else {
                    print $db_iface->report();
                } //üres is lehet a kvíz címe mező*/

        } elseif($_POST['action'] == 'new_question') { //új kérdést viszünk be
                $result = $db_iface->query('SELECT * FROM `{PREFIX}promocio` WHERE `id`={ID};',array('ID'=>$_POST['quiz_id']));
                if($result === false || mysql_num_rows($result) == 0) {
                    $quiz = false;
                } else {
                        $quiz = mysql_fetch_assoc($result);
                        $siker = false;
                        if(isset($_POST['valaszok']) && !empty($_POST['valaszok']) && is_numeric($_POST['valaszok']) && 2<=$_POST['valaszok']) { //érkezzenek postból válaszok és legalább 2 legyen!
                                $valaszok = array();
                                for($i=0;$i<$_POST['valaszok'];$i++) {
                                        if(isset($_POST['valasz_'.$i]) && !empty($_POST['valasz_'.$i]))
                                                $valaszok[$i] = $_POST['valasz_'.$i];
                                      
                                }
                                if(isset($_POST['kerekmeg']) && !empty($_POST['kerekmeg'])) {
                                        $valaszok[] = '';
                                } else {
                                        if(!isset($_POST['kerdes']) || empty($_POST['kerdes'])) {
                                                $errors[] = 'Üresen hagytad a kérdés címét.';
                                        } elseif(count($valaszok) < 2) {
                                                $errors[] = 'Kettőnél kevesebb válasszal nincs értelme egy kérdésnek.';
                                        } elseif(!isset($_POST['helyes']) || !is_numeric($_POST['helyes']) || !isset($valaszok[$_POST['helyes']])) {
                                                $errors[] = 'Nem jelölted ki a helyes választ.';
                                        } else {
                                                $success = $db_iface->query('INSERT INTO `{PREFIX}kerdes` (`id`, `promocio_id`, `szoveg`, `help_url`) VALUES (NULL, \'{QUIZ}\', \'{KERD}\', \'{HELP}\');',array('QUIZ'=>$_POST['quiz_id'],'KERD'=>$_POST['kerdes'], 'HELP'=>$_POST['help_url'])); // a kérdés tábla több mezőjébe is új étékeket szúr be a postokból
                                                if(!$success) $errors[] = "A kérdés mentése sikertelen.";
                                                else {
                                                        $kerdes_id = $db_iface->last_inserted_id(); //,?? csak egy id-t használ
                                                        foreach($valaszok as $key => $valasz) { //végig megy a válasz tömbbön, hogy id-vel is lehessen hivatkozni rá
                                                                $succes = $db_iface->query('INSERT INTO `{PREFIX}valasz` (`id`, `kerdes_id`, `szoveg`, `helyes`) VALUES (NULL, \'{KERD}\', \'{VAL}\', \'{HE}\');',array('KERD'=>$kerdes_id,'VAL'=>$valasz,'HE'=>(($key==$_POST['helyes'])?1:0))); //adatbázisban beszúrja a válasz táblába az értékeket postból és beállítja, hogy helyes-e
                                                                if(!$success) $errors[] = "A válasz mentése sikertelen"; //kiíratjuk a MySQL hibát
                                                        }
                                                }
                                        }
                                        $siker = count($errors) == 0;
                                }
                        }
                        if($siker) {
                        } else { // a kérdést nem sikerült hozzáadnia az adatbázishoz esete
                                
                                if(!isset($valaszok)) { // ha nem érkeztek válaszok
                                        $valaszok = array('',''); //két üres értékű válaszom lesz deafault
                                } elseif(count($valaszok)==0) {
                                        $valaszok[] = ''; $valaszok[] = ''; // ugyanaz, mint a $valaszok = array('','');
                                } elseif(count($valaszok)==1) {
                                        $valaszok[] = '';
                                }
                             
                                foreach($valaszok as $key => $valasz) {
                                        $helyes = (isset($_POST['helyes']) && $_POST['helyes'] == $key)?' checked':''; //megjegyezte a helyes választ 
                                }
                        }	//ha nem sikerült kérdést elküldeni, akkor megjeleníti, hogy most elküldheted vagy új kérdést tehetsz fel
                }
        } elseif($_POST['action'] == 'remove_question') { //kérdés törlése esete, amikor nem sikerült
                remove_question($_POST['del']);
        } 
} else { //kvíz lista megjelenítésének esete
        $result = $db_iface->query('SELECT * FROM `{PREFIX}promocio`;');
        $kvizek = array();
        while($sor = mysql_fetch_assoc($result)){
                $kerdes_szam = $db_iface->num_rows('SELECT * FROM `{PREFIX}kerdes` WHERE `promocio_id`={ID};',array('ID' => $sor['id']));
                $kvizek[] = array(
                    'id' => $sor['id'],
                    'title' => $sor['nev'],
                    'kerdes_szam' => $kerdes_szam                    
                );
        }
}

?>

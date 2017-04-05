<?php

	checkPermission('kviz');
	$errors = array();
        
	if(! isset($_GET["promotion_id"])) {
		die("Nincs kiválasztott promóció.");
	}
	$promotion_id = $_GET["promotion_id"];
	$db_iface = new MySQLDatabase();

        //kvíz megjelenítés
	/*A $promotion_id azonosítójú kvízt jeleníti meg*/
	
	//ellenőrzés hogy létezik-e ez az azonosító + a cím lekérdezése
	$result = $db_iface->query(
                'SELECT * FROM `{PREFIX}promocio` WHERE `{PREFIX}promocio`.`id`={ID};',
                array('ID'=>$promotion_id));
	$rows = ($result)?mysql_num_rows($result):0;
	if($rows == 0) {
		print 'Az id='.$promotion_id.' kvíz nem létezik.';
		return;
	}
	$row = mysql_fetch_assoc($result); //kvíz adattábla egy sorát adja vissza
	$promotion_nev = $row['nev'];  //kvíz cimét lementi
        $promotion_datum= $row['datum']; //kvíz záró dátumát lementi
	//munkamenet nyitása, ha még nem volt elkezdve
	if(!isset($_SESSION)) //@ - PHP hibaüzenetek elnyomása
		die('Probléma a munkamenetekkel!');
	
	//a felhasználó által már megválaszolt kérdések eltárolása/betöltése
	$answers = array();
        $helyesek = 0; // helyesen megválaszolt kérdések száma
	if(!isset($_SESSION['quiz-'.$promotion_id."-".$_SESSION["user"]["id"]])){
		$_SESSION['quiz-'.$promotion_id."-".$_SESSION["user"]["id"]] = array();
	} else {
		$answers = &$_SESSION['quiz-'.$promotion_id."-".$_SESSION["user"]["id"]]; // $answers álneve lett a $_SESSION['quiz-'.$promotion_id."-".$_SESSION["user"]["id"]] munkamenetváltozónak
	
		/*A beérkező válasz feldolgozása*/
		if(isset($_POST['answer']) && is_numeric($_POST['answer']) &&
		   isset($_POST['question']) && is_numeric($_POST['question']) &&
		   $db_iface->num_rows('SELECT * FROM `{PREFIX}valasz` WHERE `id`={ANSWER} AND `kerdes_id`={QUESTION};'
						 ,array('ANSWER'=>$_POST['answer'],'QUESTION' =>$_POST['question'])) != 0) { //a Post-ból érkező számot beírja az adatbázisba és úgy hajtja végre az SQL lekérdezést
			//létezik ez a kérdés és a válasz, és összetartoznak
			if(isset($answers[$_POST['question']])) { // a tömbben már létezik kulcs érték pár
				$error = 'Ez furcsa, egyszer már válaszoltál erre a kérdésre, na sebaj';
			}
			$answers[$_POST['question']] = $_POST['answer'];//???
		} else {
			$error = 'Nem létezik a kérdés, vagy a válasz, vagy nem tartoznak össze';
		}
	}
        
	$query_string = 'SELECT * FROM `{PREFIX}teljesites` WHERE `{PREFIX}teljesites`.`promocio_id`={PROMOCIO_ID} AND `{PREFIX}teljesites`.`felhasznalo_id`={USER_ID};';
	$params = array('PROMOCIO_ID'=>$promotion_id, 'USER_ID'=>$_SESSION["user"]["id"]);
	$result = $db_iface->num_rows($query_string ,$params); //Lekérdezzük a teljesites táblából, hogy a felhasználó kitöltötte-e már a kvízt
 	//ez a változó jelöli hogy befejeztük-e a quizt
	$kitoltott; //egy korábbi kviz kitöltés megléte   
	$jol_toltotte_ki;
        if($result == 0) {
            $kitoltott = false;
        } else {
			$query_string = 'SELECT * FROM `{PREFIX}teljesites` WHERE `{PREFIX}teljesites`.`promocio_id`={PROMOCIO_ID} AND `{PREFIX}teljesites`.`felhasznalo_id`={USER_ID};';
			$params = array('PROMOCIO_ID'=>$promotion_id, 'USER_ID'=>$_SESSION["user"]["id"]);
			$result = $db_iface->query($query_string ,$params); //Lekérdezzük a teljesites táblából, hogy a felhasználó kitöltötte-e már a kvízt
            $row = mysql_fetch_array($result);	
			$jol_toltotte_ki = $row["teljesult_e"] == 0 ? false : true;
			$kitoltott = true;
        }
        
        $finished = true;
        //lássuk mi az első olyan kérdés amit még nem válaszoltunk meg
        $question_id;
        $question_text;
        $question_help;  
        $question_num = count($answers); //megszámoljuk a feltett kérdéseinket
        //kérdések betöltése az adatbázisból
        $result = $db_iface->query('SELECT * FROM `{PREFIX}kerdes` WHERE `{PREFIX}kerdes`.`promocio_id`={ID};',array('ID'=>$promotion_id));
        //ez a változó jelöli hogy befejeztük-e a quizt

        while($row = mysql_fetch_assoc($result)) {
                if(!in_array($row['id'], array_keys($answers))) { //ha ez a kérdés még nem lett feltéve, kulcsok között keres
                        $finished = false;  //ha van egy megválaszolatlan kérdés, akkor még nem végeztünk
                        $question_id = $row['id'];
                        $question_text = $row['szoveg']; //felteszi a kérdést, aminek az id-jét megszereztük
                        $question_help = $row['help_url'];
                        break;
                }
        }
        $question_num_all = ($result)?mysql_num_rows($result):0; //hány kérdésünk van, ha nincs sora, akkor 0
        $solutions = array();
        /* példa a solution-ra
        $solutions = array(
              "Ki vagy te?" => array(
                  "valasz" => "Valaki",
                  "helyes_valasz" => "Hát te"
              ),
              "Ki vagyok én?" => array(
                  "valasz" => "Te",
                  "helyes_valasz" => "Én"
              )
        );
        */

	$new_question;
	if($finished || $kitoltott) { // ha az aktuális munkamenetben vagy egy korábbiban befejeztük a kvízt
		foreach($answers as $q_id => $a_id) { // bejárjuk az asszociatív $answer tömbot, ahol az aktuális párból $q_id tárolja a kulcsot és $a_id az értéket
			//a kérdés címe
			$result = $db_iface->query('SELECT * FROM `{PREFIX}kerdes` WHERE `id`={Q_ID};',array('Q_ID'=>$q_id));
			$result = mysql_fetch_assoc($result);
			if($result['promocio_id'] != $promotion_id) {
                            die ('Munkamenet kezelési hiba!'); //ellenőrzés (ha az előző kvízből benne maradt volna egy kérdés a sessionben, akkor meghal a program)
                        }
                        $kerdes_szoveg = $result['szoveg'];
			//a válaszod
			$result = mysql_fetch_assoc($db_iface->query('SELECT * FROM `{PREFIX}valasz` WHERE `id`={A_ID};',array('A_ID'=>$a_id)));
			$valasz = $result['szoveg'];
			$solutions[$kerdes_szoveg] = array();//asszociációs tömb, aminek egyik eleme egy másik tömb, aminek minden eleméhez egy válasz (psotból) és helyes válasz(adatbázisból) szövege tartozik
                        $solutions[$kerdes_szoveg]["valasz"] = $valasz;//kiíratni, hogy lássam
			if(! $result['helyes']) {  //ha nem helyes a válasz, akkor adatbázisból kiszedjük a helyes választ
				$result = mysql_fetch_assoc($db_iface->query('SELECT * FROM `{PREFIX}valasz` WHERE (`kerdes_id`={Q_ID} AND `helyes` = 1);',array('Q_ID'=>$q_id)));
				$solutions[$kerdes_szoveg]["helyes_valasz"] = $result['szoveg'];//példa feljebb
			}
                        else {
                            $helyesek++;
                        }
                        
		}
                
                // Kitöltött kvíz?
                $felhasznalo_id = $_SESSION["user"]['id'];
                $num_rows = $db_iface->num_rows('SELECT * FROM `{PREFIX}teljesites` WHERE `promocio_id`={PROMOTION_ID} AND `felhasznalo_id`={FELHASZNALO_ID};',
                       array('PROMOTION_ID'=>$promotion_id,'FELHASZNALO_ID'=>$felhasznalo_id ));
                
                if ($num_rows == 0){
                // helyes megoldás tényét elmentjük                    
                    if($helyesek == count($solutions)){ //$teljesult_e = $helyesek == count($_esolutions);
                        $teljesult_e = 1;
						$jol_toltotte_ki = true;
                    } else {
                        $teljesult_e = 0; 
                    }

                    $query_string = 'INSERT INTO `{PREFIX}teljesites` (`felhasznalo_id`, `promocio_id`, `teljesult_e`) ' . 
                                    'VALUES ( \'{FELHASZNALO_ID}\', \'{PROMOTION_ID}\',\'{TELJESULT_E}\');';
                    $query_params = array('FELHASZNALO_ID'=>$felhasznalo_id, 'PROMOTION_ID'=>$promotion_id, 'TELJESULT_E'=>$teljesult_e);
                    if(! $db_iface->query($query_string,$query_params)) {
                        print $db_iface->report();
                        die("Adatbázis lekérdezési hiba.");
                    }
                }
        unset($_SESSION['quiz-'.$promotion_id."-".$_SESSION["user"]["id"]]); // session ürítés
	} else { //ha nincs befejezve a kvíz -- 2. állapot
		/*mutassuk a következő kérdést*/
		//a kérdéshez tartozó válaszokat töltjük be
                                  
                // példa a solution-ra
               /* $new_question = array(
                        "question_id" => 23,
                        "question_text" => "Ki vagy te?",
                        "answers" => array(
                            3 => "Én",
                            5 => "Te",
                            10 => "Ő",
                            13 => "Mi",
                            11 => "Ti",
                            41 => "Ők"
                        )
                );
                */
                $new_question["question_id"] = $question_id;
                $new_question["question_text"] = $question_text;
                $new_question["answers"] = array();
		$result = $db_iface->query('SELECT * FROM `{PREFIX}valasz` WHERE `{PREFIX}valasz`.`kerdes_id`={QID};',array('QID'=>$question_id)); //adatbázisból megszerezzük a kérdéshez tartozó válaszokat
		while($row = mysql_fetch_assoc($result)) {
                    $answer_id = $row['id'];
                    $new_question["answers"][$answer_id] = $row['szoveg']; // a $new_question-nak van egy "answers" tömbje, ami answereket tárol. 1-1 answer pedig egy olyan tömb, ami tárol 1 id-t és egy szöveget
		}
	}
        
?>
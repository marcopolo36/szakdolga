<?php
        include ('mysqldatabase.php');

	$page_title = "Kezdőlap";
	$menu = array(
		"kezdolap"=>"Kezdőlap", 
		"regisztracio"=>"Regisztráció",
		"uzenetkuldes"=>"Üzenetküldés",
		"kvizjatek"=>"Kvízjáték",
                "kapcsolat"=>"Kapcsolat",
                "bejelentkezes"=>"Bejelentkezés"
		);
	$page_main_title = "Kvízjáték oldal!";
	$page_content = "";
        
        $promotion_id = $_GET["promotion_id"];
        $db_iface = new MySQLDatabase();

        //header('Content-type: text/html; charset=iso-8859-2');

        //kvíz megjelenítés

	global $db_iface;
	/*A $id azonosítójú kvízt jeleníti meg*/
	
	//ellenőrzés hogy létezik-e ez az azonosító + a cím lekérdezése
	$result = $db_iface->query('SELECT * FROM `{PREFIX}promocio` WHERE `{PREFIX}promocio`.`id`={ID};',array('ID'=>$promotion_id));
	$rows = ($result)?mysql_num_rows($result):0;
	if($rows == 0) {
		print 'Az id='.$id.' kvíz nem létezik.';
		return;
	}
	$row = mysql_fetch_assoc($result); //kvíz adattábla egy sorát adja vissza
	$promotion_nev = $row['nev'];  //kvíz cimét lementi
	
	//munkamenet nyitása, ha még nem volt elkezdve
	if(!isset($_SESSION) && false === @session_start()) //@ - PHP hibaüzenetek elnyomása
		die('Probléma a munkamenetekkel!');
	
	//a felhasználó által már megválaszolt kérdések eltárolása/betöltése
	$answers = array();
	if(!isset($_SESSION['quiz-'.$promotion_id]) || isset($_POST['reset_quiz'])){
		$_SESSION['quiz-'.$promotion_id] = array();
	} else {
		$answers = &$_SESSION['quiz-'.$promotion_id]; // $answers az álneve lett a $_SESSION['quiz-'.$id] munkamenetváltozónak
	
		/*A beérkező válasz feldolgozása*/
		if(isset($_POST['answer']) && is_numeric($_POST['answer']) &&
		   isset($_POST['question']) && is_numeric($_POST['question']) &&
		   $db_iface->num_rows('SELECT * FROM `{PREFIX}valasz` WHERE `id`={ANSWER} AND `kerdes`={QUESTION};'
						 ,array('ANSWER'=>$_POST['answer'],'QUESTION' =>$_POST['question'])) != 0) { //a Post-ból érkező számot beírja az adatbázisba és úgy hajtja végre az SQL lekérdezést
			//létezik ez a kérdés és a válasz, és összetartoznak
			if(isset($answers[$_POST['question']])) { // a tömbben már létezik kulcs érték pár
				$error = 'Ez furcsa, egyszer már válaszoltál erre a kérdésre, na sebaj';
			}
			$answers[$_POST['question']] = $_POST['answer'];
		} else {
			$error = 'Nem létezik a kérdés, vagy a válasz, vagy nem tartoznak össze';
		}
	}
	
	//kérdések betöltése az adatbázisból
	$result = $db_iface->query('SELECT * FROM `{PREFIX}kerdes` WHERE `{PREFIX}kerdes`.`promocio_id`={ID};',array('ID'=>$promotion_id));
	//ez a változó jelöli hogy befejeztük-e a quizt
	$finished = true;
	//lásuk mi az első olyan kérdés amit még nem válaszoltunk meg
        $question_id;
        $question_text;
	while($row = mysql_fetch_assoc($result)) {
		if(!in_array($row['id'],array_keys($answers))) { //ha ez a kérdés még nem lett feltéve, kulcsok között keres
			$finished = false;  //ha van egy megválaszolatlan kérdés, akkor még nem végeztünk
			$question_id = $row['id'];
                        $question_text = $row['szoveg']; //felteszi a kérdést, aminek az id-jét megszereztük
			break;
		}
	}
	$question_num_all = ($result)?mysql_num_rows($result):0; //hány kérdésünk van, ha nincs sora, akkor 0
	$question_num = count($answers); //megszámoljuk a feltett kérdéseinket
        $solutions = array();
	$new_question;	
	if($finished) { // ha befejeztük a kvízt
		foreach($answers as $q_id => $a_id) { // bejárjuk az asszociatív $answer tömbot, ahol az aktuális párból $q_id tárolja a kulcsot és $a_id az értéket
			//a kérdés címe
			$result = $db_iface->query('SELECT * FROM `{PREFIX}kerdes` WHERE `id`={Q_ID};',array('Q_ID'=>$q_id));
			$result = mysql_fetch_assoc($result);
			if($result['kviz'] != $id) continue; //ellenőrzés
                        $kerdes_szoveg = $result['szoveg'];
			//a válaszod
			$result = mysql_fetch_assoc($db_iface->query('SELECT * FROM `{PREFIX}valasz` WHERE `id`={A_ID};',array('A_ID'=>$a_id)));
			$valasz = $result['szoveg'];
			$solutions[$kerdes_szoveg] = array();
                        $solutions[$kerdes_szoveg]["valasz"] = $valasz;
			if(! $result['helyes']) {  //ha nem helyes a válasz, akkor adatbázisból kiszedjük a helyes választ
				$result = mysql_fetch_assoc($db_iface->query('SELECT * FROM `{PREFIX}valasz` WHERE (`kerdes_id`={Q_ID} AND `helyes` = 1);',array('Q_ID'=>$q_id)));
				$solutions[$kerdes_szoveg]["helyes_valasz"] = $result['valasz'];
			}
		}
	} else { //ha nincs befejezve a kvíz
		/*mutassuk a következő kérdést*/
		//a kérdéshez tartozó válaszokat töltjük be
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
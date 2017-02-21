<?php
        include ('mysqldatabase.php');

	$page_title = "Kvíz";
	$menu = array(
		"kezdolap"=>"Kezdőlap", 
		"regisztracio"=>"Regisztráció",
		"uzenetkuldes"=>"Üzenetküldés",
		"kvizjatek"=>"Kvízjáték",
                "kapcsolat"=>"Kapcsolat",
                "bejelentkezes"=>"Bejelentkezés",
                "admin"=>"Adminisztráció"
		);
	$page_main_title = "Titkos üzeneted kvízjátéka!";
	$page_content = "";
        
        $uzenet_id;
        if(isset($_GET["uzenet_id"])) {
            $uzenet_id = $_GET["uzenet_id"];
        } else {
            print "Hiba: hibás üzenet id";
        }
        
        $db_iface = new MySQLDatabase();

        if(!isset($_POST['valasz'])) { // 1. állapot: form kitöltése
            $result = $db_iface->query(
                'SELECT * FROM `{PREFIX}uzenet` WHERE `{PREFIX}uzenet`.`id`={ID};',
                array('ID'=>$uzenet_id));
            $rows = ($result)?mysql_num_rows($result):0;
            if($rows == 0) {
                    print 'Az id='.$uzenet_id.' kvíz nem létezik.';
                    return;
            }
            $row = mysql_fetch_assoc($result); //üzenet adattábla egy sorát adja vissza, betöltöm egy asszociációs tömmbe utána kulcs és érték párokat rendelek hozzá
        } else { // 2. állapot: a válaszok kiértékelés
            // TODO
        }
        
        /*

	} else {
		$answers = &$_SESSION['quiz-'.$promotion_id]; // $answers álneve lett a $_SESSION['quiz-'.$id] munkamenetváltozónak
	
		//A beérkező válasz feldolgozása
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
		
	if($finished) { // ha befejeztük a kvízt
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
	} else { //ha nincs befejezve a kvíz
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
                *//*
                $new_question["question_id"] = $question_id;
                $new_question["question_text"] = $question_text;
                $new_question["answers"] = array();
		$result = $db_iface->query('SELECT * FROM `{PREFIX}valasz` WHERE `{PREFIX}valasz`.`kerdes_id`={QID};',array('QID'=>$question_id)); //adatbázisból megszerezzük a kérdéshez tartozó válaszokat
		while($row = mysql_fetch_assoc($result)) {
                    $answer_id = $row['id'];
                    $new_question["answers"][$answer_id] = $row['szoveg']; // a $new_question-nak van egy "answers" tömbje, ami answereket tárol. 1-1 answer pedig egy olyan tömb, ami tárol 1 id-t és egy szöveget
		}
	}*/
        
?>
<?php
//függvény és osztálydefiníciók

class MySQLDatabase {
	private $_connection;
	private $prefix;
	private $last='';
	
	function __construct($host, $user, $password, $db_iface, $table_prefix, $charset) {
		$this->prefix = $table_prefix;
		$this->_connection = mysql_connect($host, $user, $password);
		mysql_select_db($db_iface, $this->_connection);
		mysql_query("SET NAMES $charset;");
	}
	
	function query($query_string,$vars=array()) {
		foreach($vars as $key => $var) { //????
			$query_string = str_replace('{'.$key.'}',mysql_real_escape_string($var),$query_string);
		}
		$query_string=str_replace('{PREFIX}',$this->prefix,$query_string);
		$this->last = $query_string;
		return @mysql_query($query_string);
	}
	
	function num_rows($query_string,$vars) {
		$result = $this->query($query_string,$vars);
		return ($result)?mysql_num_rows($result):0;
	}
	
	function insert_id() {
		return mysql_insert_id($this->_connection);
	}
	
	function report() {
		return mysql_errno($this->_connection).': '.mysql_error($this->_connection).'<br>'.$this->last;
	}
	
}

// kviz.php
//kapcsolódás az adatbázishoz
if(!@include("config.php")) 
	die('úgy tûnik a program még nincs telepítve :(');

$db_iface = new MySQLDatabase($db['host'],$db['user'],$db['password'],$db['dbname'],$db['table_prefix'],'latin2');
define('PREFIX',$db['table_prefix']);

header('Content-type: text/html; charset=iso-8859-2');

//kvíz megjelenítés
function quiz($id) {
	global $db_iface;
	/*A $id azonosítójú kvízt jeleníti meg*/
	
	//ellenõrzés hogy létezik-e ez az azonosító + a cím lekérdezése
	$result = $db_iface->query('SELECT * FROM `{PREFIX}kviz` WHERE `{PREFIX}kviz`.`id`={ID};',array('ID'=>$id));
	$rows = ($result)?mysql_num_rows($result):0;
	if($rows == 0) {
		print 'Az id='.$id.' kvíz nem létezik.';
		return;
	}
	$title = mysql_fetch_assoc($result); //kvíz adattábla egy sorát adja vissza
	$title = $title['title'];  //kvíz cimét lementi
	
	//munkamenet nyitása, ha még nem volt elkezdve
	if(!isset($_SESSION) && false === @session_start()) //@ - PHP hibaüzenetek elnyomása
		die('Probléma a munkamenetekkel!<br>a fájlba ahol felhasználod a quiz scriptet, feltétlenül írd be legelõre, hogy &lt;?php session_start(); ?&gt<br>még szóköz se legyen elõtte!');
	
	//a felhasználó által már megválaszolt kérdések eltárolása/betöltése
	$answers = array();
	if(!isset($_SESSION['quiz-'.$id]) || isset($_POST['reset_quiz'])){
		$_SESSION['quiz-'.$id] = array();
		/*-------------------*/
		/*---ÜDVÕZLÕSZÖVEG---*/
		/*-------------------*/
	} else {
		$answers = &$_SESSION['quiz-'.$id]; // $answers az álneve lett a $_SESSION['quiz-'.$id] munkamenetváltozónak
	
		/*A beérkezõ válasz feldolgozása*/
		if(isset($_POST['answer']) && is_numeric($_POST['answer']) &&
		   isset($_POST['question']) && is_numeric($_POST['question']) &&
		   $db_iface->num_rows('SELECT * FROM `{PREFIX}valasz` WHERE `id`={ANSWER} AND `kerdes`={QUESTION};'
						 ,array('ANSWER'=>$_POST['answer'],'QUESTION' =>$_POST['question'])) != 0) { //a Post-ból érkezõ számot beírja az adatbázisba és úgy hajtja végre az SQL lekérdezést
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
	$result = $db_iface->query('SELECT * FROM `{PREFIX}kerdes` WHERE `{PREFIX}kerdes`.`kviz`={ID};',array('ID'=>$id));
	//ez a változó jelöli hogy befejeztük-e a quizt
	$finished = true;
	//lásuk mi az elsõ olyan kérdés amit még nem válaszoltunk meg
	while($row = mysql_fetch_assoc($result)) {
		if(!in_array($row['id'],array_keys($answers))) { //ha ez a kérdés még nem lett feltéve, kulcsok között keres
			$finished = false;  //ha van egy megválaszolatlan kérdés, akkor még nem végeztünk
			$question_id = $row['id']; $question_text = $row['kerdes']; //felteszi a kérdést, aminek az id-jét megszereztük
			break;
		}
	}
	$question_num_all = ($result)?mysql_num_rows($result):0; //hány kérdésünk van, ha nincs sora, akkor 0
	$question_num = count($answers); //megszámoljuk a feltett kérdéseinket
	print "<div class=\"quiz\"><h1>$title</h1>";
	
	if($finished) { // ha befejeztük a kvízt
		print 'Gratulálok<br />Sikeresen teljesítetted a kvízt';
		print '<table border="1">';
		print "<tr><td><b>A kérdés</b></td><td><b>Az ön által adott válasz</b></td><td><b>A helyes válasz</b></td></tr>";
		$helyesek = 0; //számlálót indít
		foreach($answers as $q_id => $a_id) { // bejárjuk az asszociatív $answer tömbot, ahol az aktuális párból $q_id tárolja a kulcsot és $a_id az értéket
			//a kérdés címe
			$result = $db_iface->query('SELECT * FROM `{PREFIX}kerdes` WHERE `id`={Q_ID};',array('Q_ID'=>$q_id));
			$result = mysql_fetch_assoc($result);
			if($result['kviz'] != $id) continue; //ellenõrzés
			$cim = $result['kerdes'];
			//a válaszod
			$result = mysql_fetch_assoc($db_iface->query('SELECT * FROM `{PREFIX}valasz` WHERE `id`={A_ID};',array('A_ID'=>$a_id)));
			$valasz = $result['valasz'];
			//a helyes válasz
			$helyes_bol = false;
			if($result['helyes']) { //ha az adatbázis logikai változója igaz (1)
				$helyes = 'ez a válasz helyes';
				$helyesek++; $helyes_bol = true; //helyes válaszokat eggyel nõveljük
			} else { //különben az adatbázisból kiszedjük a helyes választ
				$result = mysql_fetch_assoc($db_iface->query('SELECT * FROM `{PREFIX}valasz` WHERE (`kerdes`={Q_ID} AND `helyes` = 1);',array('Q_ID'=>$q_id)));
				$helyes = 'a helyes válasz: '.$result['valasz'];
			}
			
			print "<tr><td>$cim</td><td><font color=\"".(($helyes_bol)?'green':'red')."\">$valasz</font></td><td>$helyes</td></tr>"; // a válaszokat ha helyes zöld színre váltja, ha hamis pirosra
		}
		print '</table><br>';
		$ossz = count($answers); $szazalek = round(($helyesek/$ossz)*100,2); //a százalékunk egy kerekített egész szám lesz 0-100 közözött
		print "<font size=\"2em\"><b>$helyesek/$ossz helyes válasza volt<br/>$szazalek% teljesítmény</b></font>";
		print '<form method="POST"><input type="submit" name="reset_quiz" value="Töröl"/></form>';
	} else { //ha nincs befejezve a kvíz
		/*mutassuk a következõ kérdést*/
		
		print '<form method="POST"><input type="hidden" name="question" value="'.$question_id.'"/>'; // rejtett mezõben a formból megszerezzük a kérdés id-jét
		print "<h2>$question_text</h2>".++$question_num."/$question_num_all kérdés<br/>"; // a hozzá tartozó kérdés szöveget, a megválaszolt kérdések számát növeljük
		if(isset($error)) print "<!-- hiba az elõzõ kérdéskor -- $error -->"; // ha van hiba, akkor kiíratjuk a hiba tömbünkbõl
		//a kérdéshez tartozó válaszokat töltjük be
		$result = $db_iface->query('SELECT * FROM `{PREFIX}valasz` WHERE `{PREFIX}valasz`.`kerdes`={QID};',array('QID'=>$question_id)); //adatbázisból megszerezzük a kérdéshez tartozó válaszokat
		while($row = mysql_fetch_assoc($result)) {
			print '<input type="radio" name="answer" value="'.$row['id'].'"/> '.$row['valasz'].'<br />'; //kiíratjuk a kérdéshez tartozó válaszokat
		}
		print '<input type="submit" value="TOVÁBB"/> <input type="submit" name="reset_quiz" value="Töröl"/></form>'; //kíratjuk a TOVÁBB és TÖRÖL gombot
	}
	print '</div>';
}

//admin functions !!!
function remove_quiz($id) {
	global $db_iface;
	$errors = array();
	$success = $db_iface->query('DELETE FROM `{PREFIX}kviz` WHERE `id`={ID};',array('ID'=>$id));
	if(!$success) $errors[] = $db_iface->report();
	$result = $db_iface->query('SELECT * FROM `{PREFIX}kerdes` WHERE `kviz`={ID};',array('ID'=>$id));
	if(!$result) { $errors[] = $db_iface->report(); } else {
		while($sor = mysql_fetch_assoc($result)) {
			$success = $db_iface->query('DELETE FROM `{PREFIX}valasz` WHERE `kerdes`={kerdes};',array('kerdes'=>$sor['id']));
			if(!$success) $errors[] = $db_iface->report();
		}
	}
	if(!$db_iface->query('DELETE FROM `{PREFIX}kerdes` WHERE `kviz`={ID};',array('ID'=>$id))) $errors[] = $db_iface->report();
	return $errors;
}

function remove_question($id) {
	global $db_iface;
	$errors = array();
	$success = $db_iface->query('DELETE FROM `{PREFIX}valasz` WHERE `kerdes`={kerdes};',array('kerdes'=>$id));
	if(!$success) $errors[] = $db_iface->report();
	if(!$db_iface->query('DELETE FROM `{PREFIX}kerdes` WHERE `id`={ID};',array('ID'=>$id))) $errors[] = $db_iface->report();
	return $errors;
}

function remove_answer($id) {
	global $db_iface;
	$errors = array();
	$success = $db_iface->query('DELETE FROM `{PREFIX}valasz` WHERE `id`={ID};',array('ID'=>$id));
	if(!$success) $errors[]=$db_iface->report();
	return $errors;
}

function search($value,$method=true) {
	if($method)
		$global = &$_POST;
	else
		$global = &$_POST;
	$keys = array_keys($global);
	$keys_selected = array();
	foreach($keys as $key) {
		if(substr($global[$key],0,strlen($value)) == $value)
			$keys_selected[] = $key;
	}
	return $keys_selected;
}

function quiz_admin() {
	global $db_iface;
	if(isset($_POST['action'])) {
		if($_POST['action'] == 'remove') {
			$errors = remove_quiz($_POST['del']);
			
			if(count($errors) != 0) {
				print 'A kérés (mûvelet: kvíz törlése) feldolgozása közben a következõ hibák léptek föl:<br/>';
				foreach($errors as $error)
					print $error.'<br/>';
			} else {
				print 'Hiba nélkül mûködött minden<br/>';
			}
			print '<form method="POST"><input type="submit" value="VISSZA" name="vissza"/></form>';
		} elseif($_POST['action'] == 'show_quiz') {
			$id;
			if(!isset($_POST['quiz_id']) || empty($_POST['quiz_id']) || !is_numeric($_POST['quiz_id'])) {
				$id = -1;
			} else {
				$id = (int)$_POST['quiz_id'];
			}
			print '<table border="1"><tr><td>azonosító</td><td>kérdés</td><td>válaszok</td><td>törlés</td></tr>';
			$result = $db_iface->query('SELECT * FROM `{PREFIX}kerdes` WHERE `kviz`={ID};',array('ID'=>$id));
			while($sor = mysql_fetch_assoc($result)) {
				$valaszok = $db_iface->num_rows('SELECT * FROM `{PREFIX}valasz` WHERE `kerdes`={ID}',array('ID'=>$sor['id']));
				print '<tr><td>'.$sor['id'].'</td><td>'.$sor['kerdes'].'</td><td>'.$valaszok.'</td><td><form method="POST"><input type="hidden" name="action" value="remove_question"/><input type="hidden" value="'.$sor['id'].'" name="del" /><input type="submit" name="kuld" value="Törlés"/></form></td>';
			}
			print '</table><form method="POST"><input type="hidden" name="action" value="new_question"/><input type="hidden" name="quiz_id" value="'.$id.'"/><input type="submit" name="gomb" value="Új kérdés"/></form><form method="POST"><input type="submit" value="VISSZA" name="vissza"/></form>';
		} elseif($_POST['action'] == 'create') {
			//új kvíz készítése
			$success = false;
			if(isset($_POST['title']) && !empty($_POST['title'])) {
			   $success = $db_iface->query('INSERT INTO `{PREFIX}kviz` (`id`, `title`) VALUES (NULL, \'{TITLE}\');',array('TITLE'=>$_POST['title']));
			}
			if($success !== false) {
				$quiz = $db_iface->insert_id();
				print 'A quizt sikeresen létrehozta!<br/><form method="POST"><input type="hidden" name="action" value="new_question"/><input type="hidden" name="quiz_id" value="'.$quiz.'"/><input type="submit" name="gomb" value="Tovább"/></form>';
			} else {
				print '<form method="POST"><input type="hidden" name="action" value="create"/><label for="title">A quiz címe</label> <input type="text" name="title" value="'.((isset($_POST['title']))?$_POST['title']:'').'"/> <input type="submit" name="kuld" value="Létrehoz"/></form>';
			}
			
			print '<br/><form method="POST"><input type="submit" value="VISSZA" name="vissza"/></form>';
		} elseif($_POST['action'] == 'new_question') {
			$result = $db_iface->query('SELECT * FROM `{PREFIX}kviz` WHERE `id`={ID};',array('ID'=>$_POST['quiz_id']));
			if($result === false || mysql_num_rows($result) == 0) {
				print 'Hiba a kérdéses quiz (id='.$_POST['quiz_id'].') nem létezik, vagy más hiba lépett fel<br/>mysql válasza: '.$db_iface->report();
			} else {
				$result = mysql_fetch_assoc($result);
				print 'Új kérdés hozzáadása az "'.$result['title'].'" quizhez<br/>';
				$siker = false;
				if(isset($_POST['valaszok']) && !empty($_POST['valaszok']) && is_numeric($_POST['valaszok']) && 2<=$_POST['valaszok']) {
					$valaszok = array();
					for($i=0;$i<$_POST['valaszok'];$i++) {
						if(isset($_POST['valasz_'.$i]) && !empty($_POST['valasz_'.$i])) $valaszok[$i] = $_POST['valasz_'.$i]; 
						else { 
							if(isset( $_POST['helyes'] ) && is_numeric( $_POST['helyes'] )){
								if($i<$_POST['helyes'] )
									$_POST['helyes'] = ((int)$_POST['helyes'])-1;
								elseif($i==$_POST['helyes'])
									unset($_POST['helyes']);
							}
							for($seek = $i+1;$seek<$_POST['valaszok'];$seek++) {
								$_POST['valasz_'.($seek-1)] = $_POST['valasz_'.$seek];
							}
							$_POST['valaszok']--; $i--;
						}
					}
					if(isset($_POST['kerekmeg']) && !empty($_POST['kerekmeg'])) {
						$valaszok[] = '';
					} else {
						
						$errors = array();
						if(!isset($_POST['kerdes']) || empty($_POST['kerdes'])) {
							$errors[] = 'Üresen hagytad a kérdés címét';
						} elseif(count($valaszok) < 2) {
							$errors[] = 'Kettõnél kevesebb válasszal nincs értelme egy kérdésnek';
						} elseif(!isset($_POST['helyes']) || !is_numeric($_POST['helyes']) || !isset($valaszok[$_POST['helyes']])) {
							$errors[] = 'Nem jelölted ki a helyes választ';
						} else {
							$success = $db_iface->query('INSERT INTO `{PREFIX}kerdes` (`id`, `kviz`, `kerdes`) VALUES (NULL, \'{QUIZ}\', \'{KERD}\');',array('QUIZ'=>$_POST['quiz_id'],'KERD'=>$_POST['kerdes']));
							if(!$success) $errors[] = $db_iface->report(); else {
							$kerdes_id = $db_iface->insert_id();
							foreach($valaszok as $key => $valasz) {
								$succes = $db_iface->query('INSERT INTO `{PREFIX}valasz` (`id`, `kerdes`, `valasz`, `helyes`) VALUES (NULL, \'{KERD}\', \'{VAL}\', \'{HE}\');',array('KERD'=>$kerdes_id,'VAL'=>$valasz,'HE'=>(($key==$_POST['helyes'])?1:0)));
								if(!$success) $errors[] = $db_iface->report();
							}
							}
						}
						$siker = count($errors)==0;
					}
				}
				if($siker) {
					print 'A kérdés sikeresen hozzáadva az adatbázishoz<br/>';
					print '<form method="POST"><input type="hidden" name="action" value="new_question"/><input type="hidden" name="quiz_id" value="'.$_POST['quiz_id'].'"/><input type="submit" name="kuld" value="+1 kérdés"/></form>';
				} else {
					if(isset($errors))
						foreach($errors as $error) 
							print "<b><font color=\"red\">$error</b></font>";
					print '<form method="POST"><input type="hidden" name="action" value="new_question"/><input type="hidden" name="quiz_id" value="'.$_POST['quiz_id'].'"/>';
					print '<label for="kerdes">A kérdés: </label><input type="text" id="kerdes" name="kerdes" value="'.((isset($_POST['kerdes']))?$_POST['kerdes']:'').'"/><br/>';
					if(!isset($valaszok)) {
						$valaszok = array('','');
					} elseif(count($valaszok)==0) {
						$valaszok[] = ''; $valaszok[] = '';
					} elseif(count($valaszok)==1) {
						$valaszok[] = '';
					}
					print '<input type="hidden" name="valaszok" value="'.count($valaszok).'"/>';
					print '<table border="1">';
					foreach($valaszok as $key => $valasz) {
						$helyes = (isset($_POST['helyes']) && $_POST['helyes'] == $key)?' checked':'';
						print '<tr><td><input type="radio" name="helyes" value="'.$key.'"'.$helyes.'/></td><td><input type="text" name="valasz_'.$key.'" value="'.$valasz.'"/></td></tr>';
					}
					print '</table><br/><input type="submit" name="sent" value="Mehet"/> vagy <input type="submit" name="kerekmeg" value="+1 egy válaszlehetõség"/></form>';
				}
			}
			
			print '<form method="POST"><input type="submit" value="VISSZA" name="vissza"/></form>';
		} elseif($_POST['action'] == 'remove_question') {
			$errors = remove_question($_POST['del']);
			
			if(count($errors) != 0) {
				print 'A kérés (mûvelet: kvíz törlése) feldolgozása közben a következõ hibák léptek föl:<br/>';
				foreach($errors as $error)
					print $error.'<br/>';
			} else {
				print 'Hiba nélkül mûködött minden<br/>';
			}
			print '<form method="POST"><input type="submit" value="VISSZA" name="vissza"/></form>';
		} else {
			print '<form method="POST">Ismeretlen mûvelet ('.$_POST['action'].') <input type="submit" value="VISSZA" name="vissza"/></form>';
		}
	} else {
		print 'Kvízek oldaladba ágyazásához használd ezt: <b>&lt?php include("kviz.php"); quiz(15); ?&gt;</b> ahol a 15 helyére, a quiz azonosítóját írd!<br/>';
		print '<table border="1"><tr><td>azonosító</td><td>cím</td><td>kérdések</td><td>mûveletek</td></tr>';
		$result = $db_iface->query('SELECT * FROM `{PREFIX}kviz`;');
		while($sor = mysql_fetch_assoc($result)){
			$kerdes_szam = $db_iface->num_rows('SELECT * FROM `{PREFIX}kerdes` WHERE `kviz`={ID};',array('ID' => $sor['id']));
			print '<tr><td>'.$sor['id'].'</td><td>'.$sor['title'].'</td><td>'.$kerdes_szam.' db</td><td><form method="POST"><input type="hidden" name="action" value="remove"/><input type="hidden" value="'.$sor['id'].'" name="del" /><input type="submit" name="kuld" value="Törlés"/></form><form method="POST"><input type="hidden" name="action" value="show_quiz"/><input type="hidden" value="'.$sor['id'].'" name="quiz_id" /><input type="submit" name="kuld" value="Szerkesztés"/></form></td></tr>';
		}
		print '</table><form method="POST"><input type="hidden" name="action" value="create"/><input type="submit" name="send" value="Új"/></form>';
	}
}

?>
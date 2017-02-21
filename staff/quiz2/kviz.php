<?php
//f�ggv�ny �s oszt�lydefin�ci�k

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
	
	function last_inserted_id() {
		return mysql_insert_id($this->_connection);
	}
	
	function report() {
		return mysql_errno($this->_connection).': '.mysql_error($this->_connection).'<br>'.$this->last;
	}
	
}

// kviz.php
//kapcsol�d�s az adatb�zishoz
if(!@include("config.php")) 
	die('�gy t�nik a program m�g nincs telep�tve :(');

//kv�z megjelen�t�s
function quiz($id) {
	global $db_iface;
	/*A $id azonos�t�j� kv�zt jelen�ti meg*/
	
	//ellen�rz�s hogy l�tezik-e ez az azonos�t� + a c�m lek�rdez�se
	$result = $db_iface->query('SELECT * FROM `{PREFIX}kviz` WHERE `{PREFIX}kviz`.`id`={ID};',array('ID'=>$id));
	$rows = ($result)?mysql_num_rows($result):0;
	if($rows == 0) {
		print 'Az id='.$id.' kv�z nem l�tezik.';
		return;
	}
	$title = mysql_fetch_assoc($result); //kv�z adatt�bla egy sor�t adja vissza
	$title = $title['title'];  //kv�z cim�t lementi
	
	//munkamenet nyit�sa, ha m�g nem volt elkezdve
	if(!isset($_SESSION) && false === @session_start()) //@ - PHP hiba�zenetek elnyom�sa
		die('Probl�ma a munkamenetekkel!<br>a f�jlba ahol felhaszn�lod a quiz scriptet, felt�tlen�l �rd be legel�re, hogy &lt;?php session_start(); ?&gt<br>m�g sz�k�z se legyen el�tte!');
	
	//a felhaszn�l� �ltal m�r megv�laszolt k�rd�sek elt�rol�sa/bet�lt�se
	$answers = array();
	if(!isset($_SESSION['quiz-'.$id]) || isset($_POST['reset_quiz'])){
		$_SESSION['quiz-'.$id] = array();
		/*-------------------*/
		/*---�DV�ZL�SZ�VEG---*/
		/*-------------------*/
	} else {
		$answers = &$_SESSION['quiz-'.$id]; // $answers az �lneve lett a $_SESSION['quiz-'.$id] munkamenetv�ltoz�nak
	
		/*A be�rkez� v�lasz feldolgoz�sa*/
		if(isset($_POST['answer']) && is_numeric($_POST['answer']) &&
		   isset($_POST['question']) && is_numeric($_POST['question']) &&
		   $db_iface->num_rows('SELECT * FROM `{PREFIX}valasz` WHERE `id`={ANSWER} AND `kerdes`={QUESTION};'
						 ,array('ANSWER'=>$_POST['answer'],'QUESTION' =>$_POST['question'])) != 0) { //a Post-b�l �rkez� sz�mot be�rja az adatb�zisba �s �gy hajtja v�gre az SQL lek�rdez�st
			//l�tezik ez a k�rd�s �s a v�lasz, �s �sszetartoznak
			if(isset($answers[$_POST['question']])) { // a t�mbben m�r l�tezik kulcs �rt�k p�r
				$error = 'Ez furcsa, egyszer m�r v�laszolt�l erre a k�rd�sre, na sebaj';
			}
			$answers[$_POST['question']] = $_POST['answer'];
		} else {
			$error = 'Nem l�tezik a k�rd�s, vagy a v�lasz, vagy nem tartoznak �ssze';
		}
	}
	
	//k�rd�sek bet�lt�se az adatb�zisb�l
	$result = $db_iface->query('SELECT * FROM `{PREFIX}kerdes` WHERE `{PREFIX}kerdes`.`kviz`={ID};',array('ID'=>$id));
	//ez a v�ltoz� jel�li hogy befejezt�k-e a quizt
	$finished = true;
	//l�suk mi az els� olyan k�rd�s amit m�g nem v�laszoltunk meg
	while($row = mysql_fetch_assoc($result)) {
		if(!in_array($row['id'],array_keys($answers))) { //ha ez a k�rd�s m�g nem lett felt�ve, kulcsok k�z�tt keres
			$finished = false;  //ha van egy megv�laszolatlan k�rd�s, akkor m�g nem v�gezt�nk
			$question_id = $row['id']; $question_text = $row['kerdes']; //felteszi a k�rd�st, aminek az id-j�t megszerezt�k
			break;
		}
	}
	$question_num_all = ($result)?mysql_num_rows($result):0; //h�ny k�rd�s�nk van, ha nincs sora, akkor 0
	$question_num = count($answers); //megsz�moljuk a feltett k�rd�seinket
	print "<div class=\"quiz\"><h1>$title</h1>";
	
	if($finished) { // ha befejezt�k a kv�zt
		print 'Gratul�lok<br />Sikeresen teljes�tetted a kv�zt';
		print '<table border="1">';
		print "<tr><td><b>A k�rd�s</b></td><td><b>Az �n �ltal adott v�lasz</b></td><td><b>A helyes v�lasz</b></td></tr>";
		$helyesek = 0; //sz�ml�l�t ind�t
		foreach($answers as $q_id => $a_id) { // bej�rjuk az asszociat�v $answer t�mbot, ahol az aktu�lis p�rb�l $q_id t�rolja a kulcsot �s $a_id az �rt�ket
			//a k�rd�s c�me
			$result = $db_iface->query('SELECT * FROM `{PREFIX}kerdes` WHERE `id`={Q_ID};',array('Q_ID'=>$q_id));
			$result = mysql_fetch_assoc($result);
			if($result['kviz'] != $id) continue; //ellen�rz�s
			$cim = $result['kerdes'];
			//a v�laszod
			$result = mysql_fetch_assoc($db_iface->query('SELECT * FROM `{PREFIX}valasz` WHERE `id`={A_ID};',array('A_ID'=>$a_id)));
			$valasz = $result['valasz'];
			//a helyes v�lasz
			$helyes_bol = false;
			if($result['helyes']) { //ha az adatb�zis logikai v�ltoz�ja igaz (1)
				$helyes = 'ez a v�lasz helyes';
				$helyesek++; $helyes_bol = true; //helyes v�laszokat eggyel n�velj�k
			} else { //k�l�nben az adatb�zisb�l kiszedj�k a helyes v�laszt
				$result = mysql_fetch_assoc($db_iface->query('SELECT * FROM `{PREFIX}valasz` WHERE (`kerdes`={Q_ID} AND `helyes` = 1);',array('Q_ID'=>$q_id)));
				$helyes = 'a helyes v�lasz: '.$result['valasz'];
			}
			
			print "<tr><td>$cim</td><td><font color=\"".(($helyes_bol)?'green':'red')."\">$valasz</font></td><td>$helyes</td></tr>"; // a v�laszokat ha helyes z�ld sz�nre v�ltja, ha hamis pirosra
		}
		print '</table><br>';
		$ossz = count($answers); $szazalek = round(($helyesek/$ossz)*100,2); //a sz�zal�kunk egy kerek�tett eg�sz sz�m lesz 0-100 k�z�z�tt
		print "<font size=\"2em\"><b>$helyesek/$ossz helyes v�lasza volt<br/>$szazalek% teljes�tm�ny</b></font>";
		print '<form method="POST"><input type="submit" name="reset_quiz" value="T�r�l"/></form>';
	} else { //ha nincs befejezve a kv�z
		/*mutassuk a k�vetkez� k�rd�st*/
		
		print '<form method="POST"><input type="hidden" name="question" value="'.$question_id.'"/>'; // rejtett mez�ben a formb�l megszerezz�k a k�rd�s id-j�t
		print "<h2>$question_text</h2>".++$question_num."/$question_num_all k�rd�s<br/>"; // a hozz� tartoz� k�rd�s sz�veget, a megv�laszolt k�rd�sek sz�m�t n�velj�k
		if(isset($error)) print "<!-- hiba az el�z� k�rd�skor -- $error -->"; // ha van hiba, akkor ki�ratjuk a hiba t�mb�nkb�l
		//a k�rd�shez tartoz� v�laszokat t�ltj�k be
		$result = $db_iface->query('SELECT * FROM `{PREFIX}valasz` WHERE `{PREFIX}valasz`.`kerdes`={QID};',array('QID'=>$question_id)); //adatb�zisb�l megszerezz�k a k�rd�shez tartoz� v�laszokat
		while($row = mysql_fetch_assoc($result)) {
			print '<input type="radio" name="answer" value="'.$row['id'].'"/> '.$row['valasz'].'<br />'; //ki�ratjuk a k�rd�shez tartoz� v�laszokat
		}
		print '<input type="submit" value="TOV�BB"/> <input type="submit" name="reset_quiz" value="T�r�l"/></form>'; //k�ratjuk a TOV�BB �s T�R�L gombot
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
				print 'A k�r�s (m�velet: kv�z t�rl�se) feldolgoz�sa k�zben a k�vetkez� hib�k l�ptek f�l:<br/>';
				foreach($errors as $error)
					print $error.'<br/>';
			} else {
				print 'Hiba n�lk�l m�k�d�tt minden<br/>';
			}
			print '<form method="POST"><input type="submit" value="VISSZA" name="vissza"/></form>';
		} elseif($_POST['action'] == 'show_quiz') {
			$id;
			if(!isset($_POST['quiz_id']) || empty($_POST['quiz_id']) || !is_numeric($_POST['quiz_id'])) {
				$id = -1;
			} else {
				$id = (int)$_POST['quiz_id'];
			}
			print '<table border="1"><tr><td>azonos�t�</td><td>k�rd�s</td><td>v�laszok</td><td>t�rl�s</td></tr>';
			$result = $db_iface->query('SELECT * FROM `{PREFIX}kerdes` WHERE `kviz`={ID};',array('ID'=>$id));
			while($sor = mysql_fetch_assoc($result)) {
				$valaszok = $db_iface->num_rows('SELECT * FROM `{PREFIX}valasz` WHERE `kerdes`={ID}',array('ID'=>$sor['id']));
				print '<tr><td>'.$sor['id'].'</td><td>'.$sor['kerdes'].'</td><td>'.$valaszok.'</td><td><form method="POST"><input type="hidden" name="action" value="remove_question"/><input type="hidden" value="'.$sor['id'].'" name="del" /><input type="submit" name="kuld" value="T�rl�s"/></form></td>';
			}
			print '</table><form method="POST"><input type="hidden" name="action" value="new_question"/><input type="hidden" name="quiz_id" value="'.$id.'"/><input type="submit" name="gomb" value="�j k�rd�s"/></form><form method="POST"><input type="submit" value="VISSZA" name="vissza"/></form>';
		} elseif($_POST['action'] == 'create') {
			//�j kv�z k�sz�t�se
			$success = false;
			if(isset($_POST['title']) && !empty($_POST['title'])) {
			   $success = $db_iface->query('INSERT INTO `{PREFIX}kviz` (`id`, `title`) VALUES (NULL, \'{TITLE}\');',array('TITLE'=>$_POST['title']));
			}
			if($success !== false) {
				$quiz = $db_iface->last_inserted_id();
				print 'A quizt sikeresen l�trehozta!<br/><form method="POST"><input type="hidden" name="action" value="new_question"/><input type="hidden" name="quiz_id" value="'.$quiz.'"/><input type="submit" name="gomb" value="Tov�bb"/></form>';
			} else {
				print '<form method="POST"><input type="hidden" name="action" value="create"/><label for="title">A quiz c�me</label> <input type="text" name="title" value="'.((isset($_POST['title']))?$_POST['title']:'').'"/> <input type="submit" name="kuld" value="L�trehoz"/></form>';
			}
			
			print '<br/><form method="POST"><input type="submit" value="VISSZA" name="vissza"/></form>';
		} elseif($_POST['action'] == 'new_question') {
			$result = $db_iface->query('SELECT * FROM `{PREFIX}kviz` WHERE `id`={ID};',array('ID'=>$_POST['quiz_id']));
			if($result === false || mysql_num_rows($result) == 0) {
				print 'Hiba a k�rd�ses quiz (id='.$_POST['quiz_id'].') nem l�tezik, vagy m�s hiba l�pett fel<br/>mysql v�lasza: '.$db_iface->report();
			} else {
				$result = mysql_fetch_assoc($result);
				print '�j k�rd�s hozz�ad�sa az "'.$result['title'].'" quizhez<br/>';
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
							$errors[] = '�resen hagytad a k�rd�s c�m�t';
						} elseif(count($valaszok) < 2) {
							$errors[] = 'Kett�n�l kevesebb v�lasszal nincs �rtelme egy k�rd�snek';
						} elseif(!isset($_POST['helyes']) || !is_numeric($_POST['helyes']) || !isset($valaszok[$_POST['helyes']])) {
							$errors[] = 'Nem jel�lted ki a helyes v�laszt';
						} else {
							$success = $db_iface->query('INSERT INTO `{PREFIX}kerdes` (`id`, `kviz`, `kerdes`) VALUES (NULL, \'{QUIZ}\', \'{KERD}\');',array('QUIZ'=>$_POST['quiz_id'],'KERD'=>$_POST['kerdes']));
							if(!$success) $errors[] = $db_iface->report(); else {
							$kerdes_id = $db_iface->last_inserted_id();
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
					print 'A k�rd�s sikeresen hozz�adva az adatb�zishoz<br/>';
					print '<form method="POST"><input type="hidden" name="action" value="new_question"/><input type="hidden" name="quiz_id" value="'.$_POST['quiz_id'].'"/><input type="submit" name="kuld" value="+1 k�rd�s"/></form>';
				} else {
					if(isset($errors))
						foreach($errors as $error) 
							print "<b><font color=\"red\">$error</b></font>";
					print '<form method="POST"><input type="hidden" name="action" value="new_question"/><input type="hidden" name="quiz_id" value="'.$_POST['quiz_id'].'"/>';
					print '<label for="kerdes">A k�rd�s: </label><input type="text" id="kerdes" name="kerdes" value="'.((isset($_POST['kerdes']))?$_POST['kerdes']:'').'"/><br/>';
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
					print '</table><br/><input type="submit" name="sent" value="Mehet"/> vagy <input type="submit" name="kerekmeg" value="+1 egy v�laszlehet�s�g"/></form>';
				}
			}
			
			print '<form method="POST"><input type="submit" value="VISSZA" name="vissza"/></form>';
		} elseif($_POST['action'] == 'remove_question') {
			$errors = remove_question($_POST['del']);
			
			if(count($errors) != 0) {
				print 'A k�r�s (m�velet: kv�z t�rl�se) feldolgoz�sa k�zben a k�vetkez� hib�k l�ptek f�l:<br/>';
				foreach($errors as $error)
					print $error.'<br/>';
			} else {
				print 'Hiba n�lk�l m�k�d�tt minden<br/>';
			}
			print '<form method="POST"><input type="submit" value="VISSZA" name="vissza"/></form>';
		} else {
			print '<form method="POST">Ismeretlen m�velet ('.$_POST['action'].') <input type="submit" value="VISSZA" name="vissza"/></form>';
		}
	} else {
		print 'Kv�zek oldaladba �gyaz�s�hoz haszn�ld ezt: <b>&lt?php include("kviz.php"); quiz(15); ?&gt;</b> ahol a 15 hely�re, a quiz azonos�t�j�t �rd!<br/>';
		print '<table border="1"><tr><td>azonos�t�</td><td>c�m</td><td>k�rd�sek</td><td>m�veletek</td></tr>';
		$result = $db_iface->query('SELECT * FROM `{PREFIX}kviz`;');
		while($sor = mysql_fetch_assoc($result)){
			$kerdes_szam = $db_iface->num_rows('SELECT * FROM `{PREFIX}kerdes` WHERE `kviz`={ID};',array('ID' => $sor['id']));
			print '<tr><td>'.$sor['id'].'</td><td>'.$sor['title'].'</td><td>'.$kerdes_szam.' db</td><td><form method="POST"><input type="hidden" name="action" value="remove"/><input type="hidden" value="'.$sor['id'].'" name="del" /><input type="submit" name="kuld" value="T�rl�s"/></form><form method="POST"><input type="hidden" name="action" value="show_quiz"/><input type="hidden" value="'.$sor['id'].'" name="quiz_id" /><input type="submit" name="kuld" value="Szerkeszt�s"/></form></td></tr>';
		}
		print '</table><form method="POST"><input type="hidden" name="action" value="create"/><input type="submit" name="send" value="�j"/></form>';
	}
}

?>
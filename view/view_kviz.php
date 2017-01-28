<!DOCTYPE html>
<html>
<head>
<title><?php echo $page_title; ?></title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body>
<div class="container-fluid">
	
	<div id="row">
		
		<div class="col-sm-12" style="background-color:#af90af;">

			<nav class="navbar navbar-default">
			  <div class="container-fluid">
				<div class="navbar-header">
				  <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				  </button>
				  <a class="navbar-brand" href="#">Brand</a>
				</div>

				<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
				  <ul class="nav navbar-nav">
						<?php
						foreach($menu as $link => $link_text) {
							?><li><a href="index.php?site=<?php echo $link; ?>"><?php echo $link_text; ?></a></li><?php
						}
						?>	
				  </ul>
				</div>
			  </div>
			</nav>


		</div>
       
            


		<div class="col-sm-12" style="background-color:lavender;">
			<div class="panel panel-primary">
				<div class="panel-heading">
					<h3 class="panel-title">
						<?php echo $page_main_title; ?>
					</h3>
				</div>
				<div class="panel-body">
                                    
                                    
<!-- !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!! -->


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
//kapcsolódás az adatbázishoz, konfiguráció
$db["host"] = "localhost"; $db["user"] = "root"; $db["password"] = ""; $db["dbname"] = "kviz2"; $db["table_prefix"] = "qkviz_";

$db_iface = new MySQLDatabase($db['host'],$db['user'],$db['password'],$db['dbname'],$db['table_prefix'],'utf8');
define('PREFIX',$db['table_prefix']);

quiz(1);

//header('Content-type: text/html; charset=iso-8859-2');

//kvíz megjelenítés
function quiz($id) {
	global $db_iface;
	/*A $id azonosítójú kvízt jeleníti meg*/
	
	//ellenőrzés hogy létezik-e ez az azonosító + a cím lekérdezése
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
		die('Probléma a munkamenetekkel!<br>a fájlba ahol felhasználod a quiz scriptet, feltétlenül írd be legelőre, hogy &lt;?php session_start(); ?&gt<br>még szóköz se legyen előtte!');
	
	//a felhasználó által már megválaszolt kérdések eltárolása/betöltése
	$answers = array();
	if(!isset($_SESSION['quiz-'.$id]) || isset($_POST['reset_quiz'])){
		$_SESSION['quiz-'.$id] = array();
		/*-------------------*/
		/*---ÜDVŐZLŐSZÖVEG---*/
		/*-------------------*/
	} else {
		$answers = &$_SESSION['quiz-'.$id]; // $answers az álneve lett a $_SESSION['quiz-'.$id] munkamenetváltozónak
	
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
	$result = $db_iface->query('SELECT * FROM `{PREFIX}kerdes` WHERE `{PREFIX}kerdes`.`kviz`={ID};',array('ID'=>$id));
	//ez a változó jelöli hogy befejeztük-e a quizt
	$finished = true;
	//lásuk mi az első olyan kérdés amit még nem válaszoltunk meg
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
			if($result['kviz'] != $id) continue; //ellenőrzés
			$cim = $result['kerdes'];
			//a válaszod
			$result = mysql_fetch_assoc($db_iface->query('SELECT * FROM `{PREFIX}valasz` WHERE `id`={A_ID};',array('A_ID'=>$a_id)));
			$valasz = $result['valasz'];
			//a helyes válasz
			$helyes_bol = false;
			if($result['helyes']) { //ha az adatbázis logikai változója igaz (1)
				$helyes = 'ez a válasz helyes';
				$helyesek++; $helyes_bol = true; //helyes válaszokat eggyel nőveljük
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
		/*mutassuk a következő kérdést*/
		
		print '<form method="POST"><input type="hidden" name="question" value="'.$question_id.'"/>'; // rejtett mezőben a formból megszerezzük a kérdés id-jét
		print "<h2>$question_text</h2>".++$question_num."/$question_num_all kérdés<br/>"; // a hozzá tartozó kérdés szöveget, a megválaszolt kérdések számát növeljük
		if(isset($error)) print "<!-- hiba az előző kérdéskor -- $error -->"; // ha van hiba, akkor kiíratjuk a hiba tömbünkből
		//a kérdéshez tartozó válaszokat töltjük be
		$result = $db_iface->query('SELECT * FROM `{PREFIX}valasz` WHERE `{PREFIX}valasz`.`kerdes`={QID};',array('QID'=>$question_id)); //adatbázisból megszerezzük a kérdéshez tartozó válaszokat
		while($row = mysql_fetch_assoc($result)) {
			print '<input type="radio" name="answer" value="'.$row['id'].'"/> '.$row['valasz'].'<br />'; //kiíratjuk a kérdéshez tartozó válaszokat
		}
		print '<input type="submit" value="TOVÁBB"/> <input type="submit" name="reset_quiz" value="Töröl"/></form>'; //kíratjuk a TOVÁBB és TÖRÖL gombot
	}
	print '</div>';
}
?>

      
            
<!-- !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!! -->

					
				</div>
			</div>

		
		</div>
	</div>
	
</div>

</body>
</html>
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

    print "<div class=\"quiz\"><h1>".$promotion_nev."</h1>";
    
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
				$result = mysql_fetch_assoc($db_iface->query('SELECT * FROM `{PREFIX}valasz` WHERE (`kerdes_id`={Q_ID} AND `helyes` = 1);',array('Q_ID'=>$q_id)));
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
		$result = $db_iface->query('SELECT * FROM `{PREFIX}valasz` WHERE `{PREFIX}valasz`.`kerdes_id`={QID};',array('QID'=>$question_id)); //adatbázisból megszerezzük a kérdéshez tartozó válaszokat
		while($row = mysql_fetch_assoc($result)) {
			print '<input type="radio" name="answer" value="'.$row['id'].'"/> '.$row['szoveg'].'<br />'; //kiíratjuk a kérdéshez tartozó válaszokat
		}
		print '<input type="submit" value="TOVÁBB"/> <input type="submit" name="reset_quiz" value="Töröl"/></form>'; //kíratjuk a TOVÁBB és TÖRÖL gombot
	}
	print '</div>';
        
?>
      
            
<!-- !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!! -->

					
				</div>
			</div>

		
		</div>
	</div>
	
</div>

</body>
</html>
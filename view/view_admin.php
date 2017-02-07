<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
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
				<?php

                                if(isset($_POST['action'])) {
                                        if($_POST['action'] == 'remove') { //kvíz törlés esete
                                                if(count($errors) != 0) {
                                                        print 'A kérés (művelet: kvíz törlése) feldolgozása közben a következő hibák léptek föl:<br/>';
                                                        foreach($errors as $error) //bejárjuk az errors tömböt, az aktuálos hiba $error-ba lesz tárolva
                                                                print $error.'<br/>'; // kiíratom a hibát
                                                } else {
                                                        print 'Hiba nélkül működött minden<br/>';
                                                }
                                                print '<form method="POST"><input type="submit" value="VISSZA" name="vissza"/></form>'; //visszaléptető gomb
                                        } elseif($_POST['action'] == 'show_quiz') {   //kvíz megjelenítés esete
                                                print '<table border="1"><tr><td>azonosító</td><td>kérdés</td><td>válaszok</td><td>törlés</td></tr>';
                                                foreach($kerdesek as $sor ) { //mejelenítjük a kvízt, amit törölni is tudunk
                                                        print '<tr><td>'.$sor['id'].'</td><td>'.$sor['kerdes'].'</td><td>'.$sor['valaszok_szama'].'</td><td><form method="POST"><input type="hidden" name="action" value="remove_question"/><input type="hidden" value="'.$sor['id'].'" name="del" /><input type="submit" name="kuld" value="Törlés"/></form></td>';
                                                }
                                                print '</table><form method="POST"><input type="hidden" name="action" value="new_question"/><input type="hidden" name="quiz_id" value="'.$id.'"/><input type="submit" name="gomb" value="Új kérdés"/></form><form method="POST"><input type="submit" value="VISSZA" name="vissza"/></form>';
                                        } elseif($_POST['action'] == 'create') { //új kvíz készítés
                                                if($success !== false) {
                                                        print 'A quizt sikeresen létrehozta!<br/><form method="POST"><input type="hidden" name="action" value="new_question"/><input type="hidden" name="quiz_id" value="'.$quiz.'"/><input type="submit" name="gomb" value="Tovább"/></form>';
                                                } else {
                                                        print '<form method="POST"><input type="hidden" name="action" value="create"/><label for="title">A quiz címe</label> <input type="text" name="title" value="'.((isset($_POST['title']))?$_POST['title']:'').'"/> <input type="submit" name="kuld" value="Létrehoz"/></form>';
                                                } //üres is lehet a kvíz címe mező
                                                print '<br/><form method="POST"><input type="submit" value="VISSZA" name="vissza"/></form>';
                                        } elseif($_POST['action'] == 'new_question') { //új kérdést viszünk be
                                                if($quiz === false) {//$quiz az egyik ágban bool, a másik ágban asszociációs tömb, ami a modelben definiáltam
                                                        print 'Hiba a kérdéses quiz (id='.$_POST['quiz_id'].') nem létezik, vagy más hiba lépett fel<br/>mysql válasza: '.$db_iface->report(); //MySQL hiba kiíratása
                                                } else {
                                                        print 'Új kérdés hozzáadása az "'.$quiz['nev'].'" quizhez<br/>';
                                                        if($siker) {
                                                                print 'A kérdés sikeresen hozzáadva az adatbázishoz<br/>';
                                                                print '<form method="POST"><input type="hidden" name="action" value="new_question"/><input type="hidden" name="quiz_id" value="'.$_POST['quiz_id'].'"/><input type="submit" name="kuld" value="+1 kérdés"/></form>';
                                                        } else { // a kérdést nem sikerült hozzáadnia az adatbázishoz esete
                                                                if(isset($errors)) {
                                                                        foreach($errors as $error) {
                                                                                print "<b><font color=\"red\">$error</b></font>";
                                                                        }
                                                                }
                                                                print '<form method="POST"><input type="hidden" name="action" value="new_question"/><input type="hidden" name="quiz_id" value="'.$_POST['quiz_id'].'"/>';
                                                                print '<label for="kerdes">A kérdés: </label><input type="text" id="kerdes" name="kerdes" value="'.((isset($_POST['kerdes']))?$_POST['kerdes']:'').'"/><br/>'; // a sikertelenül elküldött formból postolt kerdesek
                                                                print '<input type="hidden" name="valaszok" value="'.count($valaszok).'"/>';
                                                                print '<table border="1">';
                                                                foreach($valaszok as $key => $valasz) {
                                                                        $helyes = (isset($_POST['helyes']) && $_POST['helyes'] == $key)?' checked':''; //megjegyezte a helyes választ 
                                                                        print '<tr><td><input type="radio" name="helyes" value="'.$key.'"'.$helyes.'/></td><td><input type="text" name="valasz_'.$key.'" value="'.$valasz.'"/></td></tr>'; //és bejelőli a radio gombját
                                                                }
                                                                print '</table><br/><input type="submit" name="sent" value="Mehet"/> vagy <input type="submit" name="kerekmeg" value="+1 egy válaszlehetőség"/></form>';
                                                        }	//ha nem sikerült kérdést elküldeni, akkor megjeleníti, hogy most elküldheted vagy új kérdést tehetsz fel
                                                }

                                                print '<form method="POST"><input type="submit" value="VISSZA" name="vissza"/></form>';
                                        } elseif($_POST['action'] == 'remove_question') { //kérdés törlése esete, amikor nem sikerült
                                                if(count($errors) != 0) {
                                                        print 'A kérés (művelet: kvíz törlése) feldolgozása közben a következő hibák léptek föl:<br/>';
                                                        foreach($errors as $error)
                                                                print $error.'<br/>';
                                                } else {
                                                        print 'Hiba nélkül működött minden<br/>';
                                                }
                                                print '<form method="POST"><input type="submit" value="VISSZA" name="vissza"/></form>';
                                        } else {
                                                print '<form method="POST">Ismeretlen művelet ('.$_POST['action'].') <input type="submit" value="VISSZA" name="vissza"/></form>';// elvileg felesleges
                                        }
                                } else { //kvíz lista megjelenítésének esete
                                        print '<table border="1"><tr><td>azonosító</td><td>cím</td><td>kérdések</td><td>műveletek</td></tr>';
                                        foreach($kvizek as $sor){
                                                print '<tr><td>'.$sor['id'].'</td><td>'.$sor['title'].'</td><td>'.$sor['kerdes_szam'].' db</td><td><form method="POST"><input type="hidden" name="action" value="remove"/><input type="hidden" value="'.$sor['id'].'" name="del" /><input type="submit" name="kuld" value="Törlés"/></form><form method="POST"><input type="hidden" name="action" value="show_quiz"/><input type="hidden" value="'.$sor['id'].'" name="quiz_id" /><input type="submit" name="kuld" value="Szerkesztés"/></form></td></tr>';
                                                //a kvíz id-ját, a kvíz címét és a kvízhez tartozó kérdések számát is kiírja
                                        }
                                        print '</table><form method="POST"><input type="hidden" name="action" value="create"/><input type="submit" name="send" value="Új"/></form>';
                                }

                                ?>	
				</div>
			</div>

		
		</div>
	</div>
	
</div>

</body>
</html>
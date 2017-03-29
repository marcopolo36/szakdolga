<!DOCTYPE html>
<html>
<head>
  <title><?php echo $page_title; ?></title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <script>
    function biztos() {
        if (confirm('Biztosan kijelentkezel?')) {
            window.location = "index.php?site=kijelentkezes";
        }
    }
  </script>
</head>
<body>
    <?php showErrors($errors); ?>
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
						
						<?php foreach($menu as $link => $link_text) { ?>
                                                    <?php if($link == "kijelentkezes") { ?>
                                                        <li><a onclick="biztos()" href="#"><?php echo $link_text; ?></a></li>
                                                    <?php } else { ?>
                                                        <li><a href="index.php?site=<?php echo $link; ?>"><?php echo $link_text; ?></a></li>
                                                    <?php } ?>                                                        
                                                <?php } ?>
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
				<?php   if(isset($_POST['action'])) {
                                        if($_POST['action'] == 'remove') { //kvíz törlés esete
                                                if(count($errors) != 0) { ?>
                                                        A kérés (művelet: kvíz törlése) feldolgozása közben a következő hibák léptek föl:<br/>
                                               <?php foreach($errors as $error)?> <!-- bejárjuk az errors tömböt, az aktuálos hiba $error-ba lesz tárolva -->
                                                        <?php  print $error; ?><br/> <!-- kiíratom a hibát -->
                                               <?php } else { ?>
                                                        Hiba nélkül működött minden<br/>
                                              <?php  } ?><form method="POST"><input type="submit" value="VISSZA" name="vissza"/></form><!--visszaléptető gomb-->
                                  <?php } elseif($_POST['action'] == 'show_quiz') {   ?> <!--kvíz megjelenítés esete-->
                                                        <table border="1"><tr><td>azonosító</td><td>kérdés</td><td>válaszok</td><td>törlés</td></tr>
                                         <?php  foreach($kerdesek as $sor ) { ?> <!--megjelenítjük a kvízt, amit törölni is tudunk -->
                                                        <tr><td><?php print $sor['id']; ?></td><td><?php print $sor['kerdes']; ?></td><td><?php print $sor['valaszok_szama']; ?></td><td><form method="POST"><input type="hidden" name="action" value="remove_question"/><input type="hidden" value="<?php print $sor['id']; ?>" name="del" /><input type="submit" name="kuld" value="Törlés"/></form></td>
                                               <?php } ?>
                                                        </table><form method="POST"><input type="hidden" name="action" value="new_question"/><input type="hidden" name="quiz_id" value="<?php print $id; ?>"/><input type="submit" name="gomb" value="Új kérdés"/></form><form method="POST"><input type="submit" value="VISSZA" name="vissza"/></form>
                                 <?php  } elseif ($_POST['action'] == 'create') {?><!--új kvíz készítés -->
                                      <?php     if($success !== false) { ?>
                                                        A kvízt sikeresen létrehozta!<br/><form method="POST"><input type="hidden" name="action" value="new_question"/><input type="hidden" name="quiz_id" value="<?php print $quiz; ?>"/><input type="submit" name="gomb" value="Tovább"/></form>
                                         <?php  } else { ?>
                                                        <form method="POST"><input type="hidden" name="action" value="create"/>
                                                        <label for="title">A kvíz címe</label> <input type="text" name="title" value="<?php print ((isset($_POST['title']))?$_POST['title']:''); ?>"/> <br/>
                                                        A kvíz lejárati dátuma: <br/>
                                                        <input type="date" name="datum" value="<?php print ((isset($_POST['datum']))?$_POST['datum']:''); ?>"> <br/>
                                                        A kvíz leírása: <br/>
                                                        <textarea name="leiras" rows="10" cols="30"><?php print ((isset($_POST['leiras']))?$_POST['leiras']:''); ?></textarea><br/>
                                                        <input type="submit" name="kuld" value="Létrehoz"/></form>
                                          <?php } ?> <!-- üres is lehet a kvíz címe mező -->
                                                        <br/><form method="POST"><input type="submit" value="VISSZA" name="vissza"/></form>
                                  <?php } elseif($_POST['action'] == 'new_question') { ?> <!-- új kérdést viszünk be -->
                                        <?php   if($quiz === false) { ?><!-- $quiz az egyik ágban bool, a másik ágban asszociációs tömb, ami a modelben definiáltam -->
                                                        Hiba a kérdéses kvíz (id= <?php print $_POST['quiz_id']; ?>) nem létezik, vagy más hiba lépett fel<br/>mysql válasza: <?php print $db_iface->report(); ?><!-- MySQL hiba kiíratása -->
                                         <?php  } else { ?>
                                                        Új kérdés hozzáadása az "<?php print $quiz['nev']; ?>" kvízhez<br/>
                                                <?php   if($siker) { ?>
                                                                A kérdés sikeresen hozzáadva az adatbázishoz<br/>
                                                                <form method="POST"><input type="hidden" name="action" value="new_question"/><input type="hidden" name="quiz_id" value="<?php print $_POST['quiz_id']; ?>"/><input type="submit" name="kuld" value="+1 kérdés"/></form>
                                                 <?php  } else { // a kérdést nem sikerült hozzáadnia az adatbázishoz esete
                                                                if(isset($errors)) {
                                                                        foreach($errors as $error) { ?>
                                                                                <b><font color=\"red\"><?php $error ?></b></font>
                                                            <?php       }
                                                                } ?>
                                                                        <form method="POST"><input type="hidden" name="action" value="new_question"/><input type="hidden" name="quiz_id" value="<?php print $_POST['quiz_id']; ?>"/>
                                                                        <label for="help">A segítség url: </label><input type="text" id="kerdes" name="help_url" value="<?php print ((isset($_POST['help_url']))?$_POST['help_url']:''); ?>"/><br/>
                                                                        <label for="kerdes">A kérdés: </label><input type="text" id="kerdes" name="kerdes" value="<?php print ((isset($_POST['kerdes']))?$_POST['kerdes']:''); ?>"/><br/> <!-- a sikertelenül elküldött formból postolt kerdesek -->
                                                                        <input type="hidden" name="valaszok" value="<?php print count($valaszok); ?>"/>
                                                                        <table border="1">
                                                          <?php foreach($valaszok as $key => $valasz) { ?>
                                                           <?php $helyes = (isset($_POST['helyes']) && $_POST['helyes'] == $key)?' checked':'' ;?><!--megjegyezte a helyes választ -->
                                                                        <tr><td><input type="radio" name="helyes" value="<?php print $key; ?>"<?php print $helyes; ?>/></td><td><input type="text" name="valasz_<?php print $key; ?>" value="<?php print $valasz; ?>"/></td></tr> <!-- és bejelőli a radio gombját -->
                                                        <?php   } ?>
                                                                        </table><br/><input type="submit" name="sent" value="Mehet"/> vagy <input type="submit" name="kerekmeg" value="+1 válaszlehetőség"/></form>
                                                 <?php  } ?>	<!-- nem sikerült kérdést elküldeni, akkor megjeleníti, hogy most elküldheted vagy új kérdést tehetsz fel -->
                                           <?php } ?>
                                                        <form method="POST"><input type="submit" value="VISSZA" name="vissza"/></form>
                                  <?php } elseif($_POST['action'] == 'remove_question') { //kérdés törlése esete, amikor nem sikerült
                                                if(count($errors) != 0) { ?>
                                                        A kérés (művelet: kvíz törlése) feldolgozása közben a következő hibák léptek föl:<br/>
                                                  <?php foreach($errors as $error)
                                                                print $error; ?><br/>
                                          <?php } else { ?>
                                                       Hiba nélkül működött minden<br/>
                                          <?php } ?>
                                                       <form method="POST"><input type="submit" value="VISSZA" name="vissza"/></form>
                                 <?php  } else { ?>
                                                       <form method="POST">Ismeretlen művelet (<?php print $_POST['action']; ?>) <input type="submit" value="VISSZA" name="vissza"/></form> <!-- elvileg felesleges -->
                                <?php   } ?>
                          <?php } else { ?> <!--kvíz lista megjelenítésének esete-->
                                                <table border="1"><tr><td>azonosító</td><td>cím</td><td>kérdések</td><td>műveletek</td></tr>
                                <?php  foreach($kvizek as $sor){  ?>
                                                       <tr><td><?php print $sor['id']; ?></td><td><?php print $sor['title']; ?></td><td><?php print $sor['kerdes_szam']; ?> db</td><td><form method="POST"><input type="hidden" name="action" value="remove"/><input type="hidden" value="<?php print $sor['id']; ?>" name="del" /><input type="submit" name="kuld" value="Törlés"/></form><form method="POST"><input type="hidden" name="action" value="show_quiz"/><input type="hidden" value="<?php print $sor['id']; ?>" name="quiz_id" /><input type="submit" name="kuld" value="Szerkesztés"/></form></td></tr>
                                                <!-- a kvíz id-ját, a kvíz címét és a kvízhez tartozó kérdések számát is kiírja -->
                                 <?php  }  ?>
                                               </table><form method="POST"><input type="hidden" name="action" value="create"/><input type="submit" name="send" value="Új"/></form>
                        <?php   } ?>	
				</div>
			</div>

		
		</div>
	</div>
	
</div>

</body>
</html>
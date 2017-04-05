								<?php   if(isset($_POST['action'])) {
                                        if($_POST['action'] == 'remove') { //kvíz törlés esete
                                                if(count($errors) != 0) { ?>
                                                        A kérés (művelet: kvíz törlése) feldolgozása közben a következő hibák léptek föl:<br/>
                                               <?php foreach($errors as $error)?> <!-- bejárjuk az errors tömböt, az aktuálos hiba $error-ba lesz tárolva -->
                                                        <?php  print $error; ?><br/> <!-- kiíratom a hibát -->
                                               <?php } else { ?>
                                                        Hiba nélkül működött minden<br/>
                                              <?php  } ?><form method="POST"><input class="btn btn-theme" type="submit" value="VISSZA" name="vissza"/></form><!--visszaléptető gomb-->
                                  <?php } elseif($_POST['action'] == 'show_quiz') {   ?> <!--kvíz megjelenítés esete-->
                                                        <table border="1" class="tablazat-1"><tr><td>Azonosító</td><td>Kérdés</td><td>Válaszok</td><td>Törlés</td></tr>
                                         <?php  foreach($kerdesek as $sor ) { ?> <!--megjelenítjük a kvízt, amit törölni is tudunk -->
                                                        <tr><td><?php print $sor['id']; ?></td><td><?php print $sor['kerdes']; ?></td><td><?php print $sor['valaszok_szama']; ?></td><td><form method="POST"><input type="hidden" name="action" value="remove_question"/><input type="hidden" value="<?php print $sor['id']; ?>" name="del" /><input class="btn btn-theme" type="submit" name="kuld" value="Törlés"/></form></td>
                                               <?php } ?>
                                                        </table><form method="POST"><input type="hidden" name="action" value="new_question"/><input type="hidden" name="quiz_id" value="<?php print $id; ?>"/><input class="btn btn-theme" type="submit" name="gomb" value="Új kérdés"/></form><form method="POST"><input class="btn btn-theme" type="submit" value="VISSZA" name="vissza"/></form>
                                 <?php  } elseif ($_POST['action'] == 'create') {?><!--új kvíz készítés -->
                                      <?php     if($success !== false) { ?>
                                                        A kvízt sikeresen létrehozta!<br/><form method="POST"><input type="hidden" name="action" value="new_question"/><input type="hidden" name="quiz_id" value="<?php print $quiz; ?>"/><input class="btn btn-theme" type="submit" name="gomb" value="Tovább"/></form>
                                         <?php  } else { ?>
                                         				<div class="uj-kviz">
                                                        <form method="POST"><input type="hidden" name="action" value="create"/>
                                                        <label for="title">A kvíz címe</label><br/> 
                                                        <input type="text" name="title" value="<?php print ((isset($_POST['title']))?$_POST['title']:''); ?>"/> <br/><br/>
                                                        <label>A kvíz lejárati dátuma:</label> <br/>
                                                        <input type="date" name="datum" value="<?php print ((isset($_POST['datum']))?$_POST['datum']:''); ?>"> <br/><br/>
                                                        <label>A kvíz leírása:</label><br/>
                                                        <textarea style="color:black" name="leiras" rows="10" cols="30"><?php print ((isset($_POST['leiras']))?$_POST['leiras']:''); ?></textarea><br/><br/>
                                                        <input class="btn btn-theme" type="submit" name="kuld" value="Létrehoz"/></form>
                                          <?php } ?> <!-- üres is lehet a kvíz címe mező -->
                                                        <br/><form method="POST"><input class="btn btn-theme" type="submit" value="VISSZA" name="vissza"/></form>
                                                        </div>
                                  <?php } elseif($_POST['action'] == 'new_question') { ?> <!-- új kérdést viszünk be -->
                                        <?php   if($quiz === false) { ?><!-- $quiz az egyik ágban bool, a másik ágban asszociációs tömb, ami a modelben definiáltam -->
                                                        Hiba a kérdéses kvíz (id= <?php print $_POST['quiz_id']; ?>) nem létezik, vagy más hiba lépett fel<br/>mysql válasza: <?php print $db_iface->report(); ?><!-- MySQL hiba kiíratása -->
                                         <?php  } else { ?>
                                                        Új kérdés hozzáadása az "<?php print $quiz['nev']; ?>" kvízhez<br/>
                                                <?php   if($siker) { ?>
                                                                A kérdés sikeresen hozzáadva az adatbázishoz<br/>
                                                                <form method="POST"><input type="hidden" name="action" value="new_question"/><input type="hidden" name="quiz_id" value="<?php print $_POST['quiz_id']; ?>"/><input class="btn btn-theme" type="submit" name="kuld" value="+1 kérdés"/></form>
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
                                                                        <table border="1" class="tablazat-2">
                                                          <?php foreach($valaszok as $key => $valasz) { ?>
                                                           <?php $helyes = (isset($_POST['helyes']) && $_POST['helyes'] == $key)?' checked':'' ;?><!--megjegyezte a helyes választ -->
                                                                        <tr><td><input type="radio" name="helyes" value="<?php print $key; ?>"<?php print $helyes; ?>/></td><td class="uj-valasz"><input style="color:black" type="text" name="valasz_<?php print $key; ?>" value="<?php print $valasz; ?>"/></td></tr> <!-- és bejelőli a radio gombját -->
                                                        <?php   } ?>
                                                                        </table><br/><input class="btn btn-theme" type="submit" name="sent" value="Mehet"/> vagy <input class="btn btn-theme" type="submit" name="kerekmeg" value="+1 válaszlehetőség"/></form>
                                                 <?php  } ?>	<!-- nem sikerült kérdést elküldeni, akkor megjeleníti, hogy most elküldheted vagy új kérdést tehetsz fel -->
                                           <?php } ?>
                                                        <form method="POST"><input  class="btn btn-theme"type="submit" value="VISSZA" name="vissza"/></form>
                                  <?php } elseif($_POST['action'] == 'remove_question') { //kérdés törlése esete, amikor nem sikerült
                                                if(count($errors) != 0) { ?>
                                                        A kérés (művelet: kvíz törlése) feldolgozása közben a következő hibák léptek föl:<br/>
                                                  <?php foreach($errors as $error)
                                                                print $error; ?><br/>
                                          <?php } else { ?>
                                                       Hiba nélkül működött minden<br/>
                                          <?php } ?>
                                                       <form method="POST"><input class="btn btn-theme" type="submit" value="VISSZA" name="vissza"/></form>
                                 <?php  } else { ?>
                                                       <form method="POST">Ismeretlen művelet (<?php print $_POST['action']; ?>) <input class="btn btn-theme" type="submit" value="VISSZA" name="vissza"/></form> <!-- elvileg felesleges -->
                                <?php   } ?>
                          <?php } else { ?> <!--kvíz lista megjelenítésének esete-->
                                                <table border="1" class="tablazat-3"><tr><td>Azonosító</td><td>Cím</td><td>Kérdések</td><td>Műveletek</td></tr>
                                <?php  foreach($kvizek as $sor){  ?>
                                                       <tr><td><?php print $sor['id']; ?></td><td><?php print $sor['title']; ?></td><td><?php print $sor['kerdes_szam']; ?> db</td><td><form method="POST"><input type="hidden" name="action" value="remove"/><input type="hidden" value="<?php print $sor['id']; ?>" name="del" /><input class="btn btn-theme" type="submit" name="kuld" value="Törlés"/></form><form method="POST"><input type="hidden" name="action" value="show_quiz"/><input type="hidden" value="<?php print $sor['id']; ?>" name="quiz_id" /><input class="btn btn-theme" type="submit" name="kuld" value="Szerkesztés"/></form></td></tr>
                                                <!-- a kvíz id-ját, a kvíz címét és a kvízhez tartozó kérdések számát is kiírja -->
                                 <?php  }  ?>
                                               </table><form method="POST"><input type="hidden" name="action" value="create"/><input class="btn btn-theme" type="submit" name="send" value="Új"/></form>
                        <?php   } ?>
 				</div>
 				<div class="col-md-2">
 					

 				</div>
	 		</div>
	 	</div><! --/container -->
	 </div><! --/service -->


	<!-- *****************************************************************************************************************
	 FOOTER
	 ***************************************************************************************************************** -->
	 <div id="footerwrap">
	 	
	 </div><! --/footerwrap -->

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
	<script src="assets/js/jquery.hoverdir.js"></script>
	<script src="assets/js/jquery.hoverex.min.js"></script>



    <script>
  jQuery(window).scroll(function()
  {
    var vscroll = jQuery(this).scrollTop();
    jQuery('#logoimage').css({
      "transform" : "translate(0px, "+vscroll/2+"px)"
	});  
  });
</script>
  </body>
</html>

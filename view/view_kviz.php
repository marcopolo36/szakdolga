				<div class="quiz"><h1><?php print $promotion_nev; ?></h1>
    <?php if($finished && ! $kitoltott) { // ha befejeztük az aktuális kvízt, éppen most ?>          
        <?php if(isset($jol_toltotte_ki) && $jol_toltotte_ki == 1) { ?>
			Gratulálok!<br />Sikeresen teljesítetted a kvízt!
		<?php } ?>
		<table border="1" class="kviz-tablazat">
		<tr><td><b>A kérdés</b></td><td><b>Az ön által adott válasz</b></td><td><b>A helyes válasz</b></td></tr>
		<?php foreach($solutions as $question => $two_answers) { // bejárjuk az asszociatív $solution tömbot, ahol az aktuális párból $question tárolja a kulcsot (a kérdés szövegét) és az $two_answers a megadott és a helyes válasz szövegeit ?>
                        <?php if(isset($two_answers["helyes_valasz"])) { //ha az adatbázis logikai változója igaz (1) ?>
                                <tr><td><?php print $question; ?></td><td><font color="red"><?php print $two_answers["valasz"];?></font></td><td><?php print $two_answers["helyes_valasz"]; ?></td></tr> <!-- a válaszokat ha helyes zöld színre váltja, ha hamis pirosra -->
			<?php } else { ?><!-- //különben az adatbázisból kiszedjük a helyes választ -->
				 <tr><td><?php print $question; ?></td><td><font color="green"><?php print $two_answers["valasz"] ;?></font></td><td>Helyes válasz</td></tr><!-- a válaszokat ha helyes zöld színre váltja, ha hamis pirosra -->
                        <?php } ?> 
                <?php } ?>      
		</table><br>
                <?php $ossz = count($solutions); ?>
                <?php $szazalek = $ossz != 0 ? round(($helyesek/$ossz)*100,2) : 0; // ha 0 kérdés van a kvízben, akkor 0%. A százalékunk egy kerekített egész szám lesz 0-100 közözött ?>
                
                <?php if($szazalek==100){ ?> 
                   <font size="2em"><b>Tökéletesen válaszoltál! A sorsolás ideje: <?php print $promotion_datum ;?></b></font>
                <?php   }else { ?> 
                <font size="2em"><b> <?php print $helyesek; ?> helyes válasza volt <?php print $szazalek; ?> % teljesítmény</b></font>
                <?php } ?>
    <?php } else if ($kitoltott) { // korábban már kitöltötte ?> 
		<!--
		/* példa tömb kiíratására a megjelenítésnél
		print "new_question: ";
                print "<pre>";
                print_r($new_question);
                print "<\pre>";*/
                /*mutassuk a következő kérdést*/
		-->
		<?php if(isset($jol_toltotte_ki) && $jol_toltotte_ki == 1) { ?>
			Ezt a kvízt már kitöltötted!<br />Ráadásul sikeresen!
		<?php } else { ?>
			Ezt a kvízt már kitöltötted!<br />Sajnos nem sikerült.
		<?php } ?>
	<?php } else { // még tölti a kvízt ?>
		<form method="POST"><input type="hidden" name="question" value="<?php  print $new_question["question_id"]; ?>"/><!--  rejtett mezőben a formból megszerezzük a kérdés id-jét -->
		<h2><?php print $new_question["question_text"]; ?></h2><?php print ++$question_num . " / " . $question_num_all; ?> kérdés<br/> <!--  a hozzá tartozó kérdés szöveget, a megválaszolt kérdések számát növeljük -->
		<?php if(isset($error)) ?> <!-- hiba az előző kérdéskor -- $error /ha van hiba, akkor kiíratjuk a hiba tömbünkből -->
		<!-- a kérdéshez tartozó válaszokat töltjük be -->
		<?php foreach($new_question["answers"] as $answer_id => $answer_text) { ?>
			<input type="radio" name="answer" value="<?php print $answer_id; ?>"/> <?php print $answer_text; ?><br /><!-- kiíratjuk a kérdéshez tartozó válaszokat -->
		<?php } ?>
		<input class="btn btn-theme" type="submit" value="TOVÁBB"/> <!--kiíratjuk a TOVÁBB gombot -->
                <button class="btn btn-theme" type="button" onclick="window.open('<?php print $question_help; ?>', '_blank');">Segítség!</button></form>
    <?php } ?>
	</div> 
          			
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

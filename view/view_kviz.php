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
                                    



    <div class="quiz"><h1><?php print $promotion_nev; ?></h1>
    <?php if($finished || $kitoltott) { // ha befejeztük a kvízt ?>          
                Gratulálok<br />Sikeresen teljesítetted a kvízt
		<table border="1">
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
		<form method="POST"><input type="submit" name="reset_quiz" value="Töröl"/></form>
                <?php } ?>
    <?php } else { //ha nincs befejezve a kvíz
		/* példa tömb kiíratására a megjelenítésnél
		print "new_question: ";
                print "<pre>";
                print_r($new_question);
                print "<\pre>";*/
                /*mutassuk a következő kérdést*/ ?>
		<form method="POST"><input type="hidden" name="question" value="<?php  print $new_question["question_id"]; ?>"/><!--  rejtett mezőben a formból megszerezzük a kérdés id-jét -->
		<h2><?php print $new_question["question_text"]; ?></h2><?php ++$question_num/$question_num_all; ?> kérdés<br/> <!--  a hozzá tartozó kérdés szöveget, a megválaszolt kérdések számát növeljük -->
		<?php if(isset($error)) ?> <!-- hiba az előző kérdéskor -- $error /ha van hiba, akkor kiíratjuk a hiba tömbünkből -->
		<!-- a kérdéshez tartozó válaszokat töltjük be -->
		<?php foreach($new_question["answers"] as $answer_id => $answer_text) { ?>
			<input type="radio" name="answer" value="<?php print $answer_id; ?>"/> <?php print $answer_text; ?><br /><!-- kiíratjuk a kérdéshez tartozó válaszokat -->
		<?php } ?>
		<input type="submit" value="TOVÁBB"/> <input type="submit" name="reset_quiz" value="Töröl"/><!--kiíratjuk a TOVÁBB és TÖRÖL gombot -->
                <button type="button" onclick="window.open('<?php print $question_help; ?>', '_blank');">Segítség!</button></form>
    <?php } ?>
	</div>
        

      
            
<!-- !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!! -->

					
				</div>
			</div>

		
		</div>
	</div>
	
</div>

</body>
</html>
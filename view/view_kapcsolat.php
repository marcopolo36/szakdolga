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
		
		<!--<p><?php /* echo $page_content; */ ?></p>-->

		<div class="col-sm-12" style="background-color:lavender;">
			<div class="panel panel-primary">
				<div class="panel-heading">
					<h3 class="panel-title">
						<?php echo $page_main_title; ?>
					</h3>
				</div>
				<div class="panel-body">
                                        <h1>
                                         Titkos üzenet küldés képeslapon kvízjátékkal
                                        </h1>
                                        <br>
                                        Felhasználó elolvassa az Felhasználási feltételeket és jóváhagyja (ezzel engedélyt ad email küldésére nyereményjáték témakörben), majd regisztrálás után (keresztnév, email cím megadása és) 1 kérdést és 3 választ visz fel egy űrlapra, valamint megjelöli a helyes választ és a keresztnevet újból beírja, amit a címzettnek ki kell találnia. Képtárból képeslap háttért választ a kvízjáték mögé és beírja a titkos üzenetet (ami max. 80 karakter). Megnézi az előnézetet és vagy visszalép, hogy másik képet válasszon vagy elküldi az üzenetet. A program a képre éget egy feliratot.
                                        Címzett képeslap képet kap az „üdvözlő szöveggel” és a tárgyban az szerepel, hogy: „Titkos üzeneted érkezett! Ezek alatt az üzenet text részében olvashatja, hogy melyik linkre kattintva olvashatja el az üzenetet. A linkre kattintva a weboldalunk titkos üzenetének kvízjátékán landol. Amennyiben játszik és a kérdésre a helyes választ jelöli be a 3 lehetőség közül, még meg kell adnia a küldő keresztnevét is (3 lehetősége van és ezt a program közli is, valamint visszafele számolja a próbálkozásokat). Amennyiben kitalálta a keresztnevet akkor elolvashatja az üzenetet. Ha szeretne válaszolni, akkor az üzenet elküldése után a „Szeretnél válaszolni az üzenetre egy saját titkos üzenettel vagy játszanál még másik kvíz játékot?” üzenet jelenik, ha rákattint akkor a weboldalunk regisztrációs oldalán találja magát.
                                        <h1>
                                        Promóciós Kvízjáték
                                        </h1>
                                        <br>
                                        A weboldalunk „kvízjáték” oldalán ráklikkel a számára érdekes kvízjátékra klikkelve a már ismert módon kell játszania, de itt 4 kör van, körönként 1 kérdéssel és 3 válasszal (amelyekből 1 helyes). Mivel ez promóciós játék ezért, (csak regisztrált játékosok játszhatnak) minden körben van egy „Segítség” feliratú gomb (amely link egy új böngésző ablakban a promóciós partner weboldalán az információt tartalmazó aloldalra viszi). A kvízjáték grafikus felülete nem tölti ki az egész képernyőt, A válaszok megadása után táblázat jelenik meg a hibás és helyes válaszokkal, kiszámolja hány helyes válasz volt és ez hány százalékos teljesítményt jelent. 4 helyes válasz megadása után - „Tökéletesen válaszoltál! A sorsolás ideje: év-hónap-nap.” – felírat jelenik meg. A lezárt játékokra kattintva nem tudjuk kitölteni a kvízt. A nyerteseket is emailben értesítjük.
                                        <br>
                                        <h1>
                                        Elérhetőség
                                        </h1>
                                        <br>
                                        Office Network Kft. 
                                        <br>
                                        <strong>Ügyfélszolgálat</strong>
                                        <br>
                                        1148 Budapest, Angol utca 38. II. emelet 224-es ajtó
                                        <br>
				</div>
			</div>

		
		</div>
	</div>
	
</div>

</body>
</html>
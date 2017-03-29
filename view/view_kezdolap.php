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
				<h1>Mit játszhatunk itt?</h1>
				<br>
				<h2>Titkos üzenet küldést képeslapon kvízjátékkal.</h2>
				Üdvözlő képeslapot küldhetünk „üdvözlő szöveggel” és egy „titkos üzenettel”. A címzett egy linkre kattintva a weboldalunk titkos üzenetének kvízjátékán landol. Amennyiben játszik és a feltett „személyre szabott” kérdésre helyes választ ad, még ki kell találnia a küldő keresztnevét és csak ezután olvashatja el a „titkos” üzenetét. „Személyes titkos üzenetét” egyedül csak a címzett láthatja.
				<h2>Promóciós Kvízjátékot.</h2>
				Weboldalunk „kvízjáték” oldalán a számunkra érdekes kvízjátékra klikkelve kérdésekre kell válaszolnunk, de mivel ezek promóciós játékok, ezért csak regisztrált játékosok játszhatnak, akik sorsoláson nyerhetik meg a szponzorok ajándékait.
				</div>
			</div>

		
		</div>
	</div>
	
</div>

</body>
</html>

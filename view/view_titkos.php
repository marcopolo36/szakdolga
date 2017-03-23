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
                                    



    <div class="quiz"><h1><?php print $_SESSION["lekerdezes"]['kerdes']; ?></h1>
    <?php if($_SESSION["allapot"] == "kerdes_form") { ?> <!-- 1. állapot kérdés - válasz -->
                <form method="POST">
		<input type="radio" name="valasz" value="0"/> <?php print $_SESSION["lekerdezes"]['valasz_1']; ?><br /><!-- kiíratjuk a kérdéshez tartozó válaszokat -->
		<input type="radio" name="valasz" value="1"/> <?php print $_SESSION["lekerdezes"]['valasz_2']; ?><br />
                <input type="radio" name="valasz" value="2"/> <?php print $_SESSION["lekerdezes"]['valasz_3']; ?><br />
                <input type="submit" value="TOVÁBB"/></form>
    <?php  } else { ?> <!-- 2. állapot - keresztnév -->
           <?php if ($_SESSION["allapot"] == "keresztnev_form" && isset($_SESSION['nev_probalkozas']) && $_SESSION['nev_probalkozas'] < 3 ){ ?> 
               <p>Helyesen válaszoltál a kérdésre! Add meg még az üzeneted küldő személy keresztnevét (nem a becenevét!), hogy elolvashassad a titkos üzenetedet </br>
                   Ne feledd csak 3 lehetőséged van!</p>
                <form method="POST">
                <input type="text" name="keresztnev" value=""><br />
                <input type="submit" value="TOVÁBB"/></form>
           <?php  } elseif($_SESSION["allapot"] == 'email_form') { ?>
                     <p>Titkos üzeneted:</><br/>
                         <?php print $_SESSION["lekerdezes"]['t_uzenet']; ?>
                     <p>Szeretnél válaszolni az üzenetre egy saját titkos üzenettel vagy játszanál még másik kvíz játékot?</><br/>
                     <form method="POST" action="index.php?site=regisztracio">
                     <input type="submit" value="Igen"/></form>               
           <?php  } elseif($_SESSION["allapot"] == "sikertelen") { ?>
                     <p>Sajnos elfogyott próbálkozásaid száma, lemaradtál a titkos üzenetről!</p>
           <?php  } else { ?>
                     <p>Sajnos hiba történt</p>
           <?php  } ?> 
                    
       <?php }?>
	</div>
             
            
<!-- !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!! -->

					
				</div>
			</div>

		
		</div>
	</div>
	
</div>

</body>
</html>
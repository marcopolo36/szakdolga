					<div class="quiz"><h1><?php print $_SESSION["lekerdezes"]['kerdes']; ?></h1>
					<?php if($_SESSION["allapot"] == "kerdes_form") { ?> <!-- 1. állapot kérdés - válasz -->
								<form method="POST">
						<input type="radio" name="valasz" value="0"/> <?php print $_SESSION["lekerdezes"]['valasz_1']; ?><br /><!-- kiíratjuk a kérdéshez tartozó válaszokat -->
						<input type="radio" name="valasz" value="1"/> <?php print $_SESSION["lekerdezes"]['valasz_2']; ?><br />
								<input type="radio" name="valasz" value="2"/> <?php print $_SESSION["lekerdezes"]['valasz_3']; ?><br />
								<input class="btn btn-theme" type="submit" value="TOVÁBB"/></form>
					<?php  } else { ?> <!-- 2. állapot - keresztnév -->
						   <?php if ($_SESSION["allapot"] == "keresztnev_form" && isset($_SESSION['nev_probalkozas']) && $_SESSION['nev_probalkozas'] < 3 ){ ?> 
							   <p style="color: white;">Helyesen válaszoltál a kérdésre! Add meg még az üzeneted küldő személy keresztnevét (nem a becenevét!), hogy elolvashassad a titkos üzenetedet </br>
								   Ne feledd csak 3 lehetőséged van!</p>
								<form method="POST">
								<input type="text" name="keresztnev" value=""><br />
								<input class="btn btn-theme" type="submit" value="TOVÁBB"/></form>
						   <?php  } elseif($_SESSION["allapot"] == 'email_form') { ?>
									 <p style="color: white;"><b>Titkos üzeneted:</b></p>
										 <h3><?php print $_SESSION["lekerdezes"]['t_uzenet']; ?></h3>
							   <?php if($_SESSION["user"]["szerepkor"] == "Anonymus") { ?>
										<p style="color: white;">Szeretnél válaszolni az üzenetre egy saját titkos üzenettel vagy játszanál még másik kvíz játékot?</p>
										<form method="POST" action="index.php?site=regisztracio">
										<input class="btn btn-theme" type="submit" value="Igen"/></form>
							   <?php } ?>
						   <?php  } elseif($_SESSION["allapot"] == "sikertelen") { ?>
									 <p style="color: white;">Sajnos elfogyott próbálkozásaid száma, lemaradtál a titkos üzenetről!</p>
						   <?php  } else { ?>
									 <p style="color: white;">Sajnos hiba történt</p>
						   <?php  } ?> 
									
					   <?php }?>
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

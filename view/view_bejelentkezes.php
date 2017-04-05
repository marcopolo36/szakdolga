						<br>
						<?php if(! isset($_SESSION['felhasznalo_nev'])) { ?>                  
                                    <h2 class="bejelentkezes-title">Bejelentkezés</h2>
                                    <form method="post" action="index.php?site=bejelentkezes">  
                                      <div class="control-label">Felhasználónév:</div>
                                      <div class="controls"><input type="text" name="nev"></div>
                                      <br>
                                      <div class="control-label">Jelszó:</div>
                                      <div class="controls"><input type="password" name="jelszo"></div>
                                      <br>
                                      <input class="btn btn-theme" type="submit" name="submit" value="Elküld">  
                                    </form>
                                <?php } else { ?>
                                    <p>Üdvözlünk <?php echo $_SESSION['felhasznalo_nev'] ?>! Már be vagy jelentkezve. </p>
                                <?php } ?>
          			<br>
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
	 	<div class="container">
		 	<div class="row">
		 		<div class="col-md-2">
		 			<h4></h4>
		 			<div class="hline-w"></div>
		 			<p></p>
		 		</div>
		 		<div class="col-md-8">
		 			<h4>Szerzői jogok</h4>
		 			<div class="hline-w"></div>
		 			<p>Copyright © Office Network Kft. 2017,  Minden jog fenntartva, a weboldalainak bármely részének bármilyen technikával történő másolására és terjesztésére. </p>
		 		</div>
		 		<div class="col-md-2">
		 			<h4></h4>
		 			<div class="hline-w"></div>
		 			<p></p>
		 		</div>

		 	</div><! --/row -->
	 	</div><! --/container -->
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

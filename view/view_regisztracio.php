						<br>
						<h2 class="regisztracio-title">Regisztráció</h2>
                                    <form method="post" action="index.php?site=regisztracio">  
                                      <div class="control-label">Felhasználónév:</div>
                                      <div class="controls"><input type="text" name="nev"></div>
                                      <br>
                                      <div class="control-label">Jelszó:</div> 
                                      <div class="controls"><input type="password" name="jelszo"></div>
                                      <br>
                                      <div class="control-label">Email cím:</div> 
                                      <div class="controls"><input type="email" name="email"></div>
                                      <br>
                                      <input type="checkbox" name="elfogadas" value="igen"> <a target="_blank" href="index.php?site=feltetelek">Felhasználási feltételeket elfogadom.</a><br>
                                      <br><br>
                                      <input class="btn btn-theme" type="submit" name="submit" value="Elküld">
                                      
                                    </form>
                                    <?php if(! $reg_sikeres) { ?>
                                        <p style="color: red;">Sikertelen regisztráció!</p>
                                        <?php if(count($errors) != 0) { ?>
                                        A következő hibák léptek föl: <br/>
                                        <?php foreach($errors as $error) { ?>
                                           <?php print $error; ?><br/>
                                        <?php } ?>
                                    <?php } ?>
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

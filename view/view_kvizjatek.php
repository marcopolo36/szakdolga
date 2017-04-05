						<br>
                        <div class="kviz-kategoriak">
						<ul>
                                       <?php $sor; ?>
                                       <?php  while ($sor = mysql_fetch_array($result)) { ?>
                                                <li><a href="index.php?site=kviz&promotion_id=<?php print $sor['id']; ?>"><span><?php  print $sor['nev']; ?></span><br/>
                                                <?php  print $sor['leiras']; ?></a></li>
                                       <?php }?>
                         </ul>
                         </div>
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

					<?php if(count($errors) == 0 && isset($_POST["kerdes"])) { ?>
                                           <p style="color: white;">Üzenetküldés sikerült!</a>
                                    <?php }  ?>
                                    <h2>Kérdés hozzáadása a titkos üzenethez kvízjátékkal</h2>
                                    <div class="uzenetkuldes-form">
                                    <form method="POST"><input type="hidden" name="action" value="new_message"/>
                                    <div class="control-label"><label for="kerdes">A kérdés: </label></div>
                                    <div class="controls"><input type="text" id="kerdes" name="kerdes" value="<?php print $_SESSION['uzenet']['KERDES'];  ?>"/></div>
                                    <br/>
                                    <div class="control-label"><label for="valaszok">A válaszok helye 1-3-ig, a helyes választ jelőld be!</label></div>
                                    <div class="controls-radio-gomb"><input type="hidden" name="valaszok" value="2"/>
                                    <table class="helyes-valasz-tablazat">
                                    	<tr>
                                            <td class="helyes-radio-gomb"><input type="radio" name="helyes" value="0" <?php if($_SESSION['uzenet']['HELYESVALASZ_SORSZAM'] == 0) print "checked='checked'"; ?>/>
                                            </td>
                                            <td class="helyes-valasz"><input type="text" name="valasz_0" value="<?php print $_SESSION['uzenet']['VALASZ_1'];  ?>"/>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="helyes-radio-gomb"><input type="radio" name="helyes" value="1" <?php if($_SESSION['uzenet']['HELYESVALASZ_SORSZAM'] == 1) print "checked='checked'"; ?>/>
                                            </td>
                                            <td class="helyes-valasz">
                                            <input type="text" name="valasz_1" value="<?php print $_SESSION['uzenet']['VALASZ_2'];  ?>"/>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="helyes-radio-gomb"><input type="radio" name="helyes" value="2" <?php if($_SESSION['uzenet']['HELYESVALASZ_SORSZAM'] == 2) print "checked='checked'"; ?>/>
                                            </td>
                                            <td class="helyes-valasz">
                                            <input type="text" name="valasz_2" value="<?php print $_SESSION['uzenet']['VALASZ_3'];  ?>"/>
                                            </td>
                                        </tr>
                                    </table>
                                            </div>
                                            <br/>
                                            <div class="control-label"><label>Titkos üzeneted szövege, amit a helyes válasz megadása után olvashat el a címzett (30 karakter):</label></div>
                                            <div class="controls"><textarea name="t_uzenet" id="t_uzenet"><?php print $_SESSION['uzenet']['T_UZENET']; ?></textarea></div><br/>
                                            
                                          <!--< <form method="post" enctype="multipart/form-data" name="form1" id="form1">-->
                                             <div class="control-label"><label>Keresztneved (nem a beceneved!):</label></div> 
                                             <div class="controls"><input type="text" name="firstname" id="firstname" text="<?php print $_SESSION['uzenet']['KERESZTNEV_KULDO']; ?>"/></div><br/>
                                             <div class="control-label"><label>Címzett email címe:</label></div> 
                                             <div class="controls"><input type="text" name="to" id="to" value="<?php print $_SESSION['uzenet']['EMAILCIM']; ?>"/></div>
                                             <br/>
                                             <div class="control-label"><label>Képre írt üdvözlő üzenet szövege (20 karakter):</label></div>
                                             <div class="controls"><input type="text" name="szoveg" id="szoveg" value="<?php print $_SESSION['uzenet']['SZOVEG']; ?>" /></div><br/><br/>   
                                             <div class="control-label"><label>A kiválasztandó kép: </label></div>
                                             <div class="controls-kepvalaszto">
                                             <div class="kep-item">
                                             <input type="radio" name="picture" value="1" <?php if($_SESSION['uzenet']['PICTURE_NUM'] == 1) print "checked='checked'"; ?>><img src="images/nevnap.jpg" alt="kep1" style="width:122px;height:87px;">
                                             </div>
                                             <div class="kep-item">
                                             <input type="radio" name="picture" value="2" <?php if($_SESSION['uzenet']['PICTURE_NUM'] == 2) print "checked='checked'"; ?>><img src="images/szulinap.jpg" alt="kep2" style="width:122px;height:87px;">
                                             </div>
                                             <div class="kep-item">
                                             <input type="radio" name="picture" value="3" <?php if($_SESSION['uzenet']['PICTURE_NUM'] == 3) print "checked='checked'"; ?>><img src="images/udvozlet.jpg" alt="kep3" style="width:122px;height:87px;">
                                              </div>
                                              </div>
                                              <div class="kuldes-gomb">
                                              <input class="btn btn-theme" type="submit" name="sent" id="button" value="Küldés"  >
                                    </form></div>
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

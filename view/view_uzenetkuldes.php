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
                                    <?php if(count($errors) == 0 && isset($_POST["kerdes"])) { ?>
                                           <p style="color: green;">Üzenetküldés sikerült!</a>
                                    <?php }  ?>
                                    <p>Kérdés hozzáadása a titkos üzenethez kvízjátékkal</><br/>
                                    <form method="POST"><input type="hidden" name="action" value="new_message"/>
                                    <label for="kerdes">A kérdés: </label><input type="text" id="kerdes" name="kerdes" value="<?php print $_SESSION['uzenet']['KERDES'];  ?>"/><br/>
                                    <label for="valaszok">A válaszok helye 1-3-ig, a helyes választ jelőld be! </label>
                                    <input type="hidden" name="valaszok" value="2"/>
                                    <table border="1"><tr>
                                            <td><input type="radio" name="helyes" value="0" <?php if($_SESSION['uzenet']['HELYESVALASZ_SORSZAM'] == 0) print "checked='checked'"; ?>/></td>
                                            <td><input type="text" name="valasz_0" value="<?php print $_SESSION['uzenet']['VALASZ_1'];  ?>"/></td></tr>
                                            <tr>
                                            <td><input type="radio" name="helyes" value="1" <?php if($_SESSION['uzenet']['HELYESVALASZ_SORSZAM'] == 1) print "checked='checked'"; ?>/></td><td>
                                            <input type="text" name="valasz_1" value="<?php print $_SESSION['uzenet']['VALASZ_2'];  ?>"/></td>
                                            </tr>
                                            <td><input type="radio" name="helyes" value="2" <?php if($_SESSION['uzenet']['HELYESVALASZ_SORSZAM'] == 2) print "checked='checked'"; ?>/></td><td>
                                            <input type="text" name="valasz_2" value="<?php print $_SESSION['uzenet']['VALASZ_3'];  ?>"/></td>
                                            </tr>
                                            </table><br/>
                                            <p>Titkos üzeneted szövege, amit a helyes válasz megadása után olvashat el a címzett (30 karakter): </p>
                                            <textarea name="t_uzenet" id="t_uzenet"><?php print $_SESSION['uzenet']['T_UZENET']; ?></textarea><br/>
                                            <p>&nbsp;</p>
                                          <!--< <form method="post" enctype="multipart/form-data" name="form1" id="form1">-->
                                             <p>A te keresztneved: 
                                              <input type="text" name="firstname" id="firstname" text="<?php print $_SESSION['uzenet']['KERESZTNEV_KULDO']; ?>"/>
                                            </p>
                                             <p>Címzett email címe: 
                                              <input type="text" name="to" id="to" value="<?php print $_SESSION['uzenet']['EMAILCIM']; ?>"/>
                                            </p>
                                             <p>&nbsp;</p>
                                            <p>Képre írt üdvözlő üzenet szövege (15 karakter): <input type="text" name="szoveg" id="szoveg" value="<?php print $_SESSION['uzenet']['SZOVEG']; ?>" /></p>
                                                 <p>&nbsp;</p>                                 
                                              <p>A kiválasztandó kép: </p>

                                              <input type="radio" name="picture" value="1" <?php if($_SESSION['uzenet']['PICTURE_NUM'] == 1) print "checked='checked'"; ?>><img src="images/nevnap.jpg" alt="kep1" style="width:97px;height:69px;"><br>
                                              <p>&nbsp;</p>
                                              <input type="radio" name="picture" value="2" <?php if($_SESSION['uzenet']['PICTURE_NUM'] == 2) print "checked='checked'"; ?>><img src="images/szulinap.jpg" alt="kep2" style="width:97px;height:69px;"><br>
                                              <p>&nbsp;</p>
                                              <input type="radio" name="picture" value="3" <?php if($_SESSION['uzenet']['PICTURE_NUM'] == 3) print "checked='checked'"; ?>><img src="images/valentinnap.jpg" alt="kep3" style="width:97px;height:69px;"><br>
                                              <p>&nbsp;</p>
                                              <p><input type="submit" name="sent" id="button" value="Küldés"  </p> 
                                    </form>
			                        
                                </div> 
                        </div>
                </div>		
        </div>
</div>

</body>
</html>
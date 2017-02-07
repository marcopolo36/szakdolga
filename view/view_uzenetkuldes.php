<!DOCTYPE html>
<html>
<head>
<title><?php echo $page_title; ?></title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body>
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
						<?php
						foreach($menu as $link => $link_text) {
							?><li><a href="index.php?site=<?php echo $link; ?>"><?php echo $link_text; ?></a></li><?php
						}
						?>	
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
                                      <form method="post" enctype="multipart/form-data" name="form1" id="form1">
                                      <p>Címzett: 
                                        <input type="text" name="to" id="to" />
                                      </p>
                                      <p>Tárgy: "Titkos üzeneted érkezett!" </p>
                                       <p>&nbsp;</p>
                                      <p>Üdvözlő üzenet: <input type="text" name="szoveg" id="szoveg" /></p>
                                           <p>&nbsp;</p>                                 
                                        <p>A kiválasztandó kép: </p>
                                        
                                        <input type="radio" name="picture" value="1"><img src="images/nevnap.jpg" alt="kep1" style="width:97px;height:69px;"><br>
                                        <p>&nbsp;</p>
                                        <input type="radio" name="picture" value="2"><img src="images/szulinap.jpg" alt="kep2" style="width:97px;height:69px;"><br>
                                        <p>&nbsp;</p>
                                        <input type="radio" name="picture" value="3"><img src="images/valentinnap.jpg" alt="kep3" style="width:97px;height:69px;"><br>
                                        <p>&nbsp;</p>
                                        <p>
                                          <input type="submit" name="button" id="button" value="Küldés" />
                                        </p>

                                     
                                    </form>
                                </div> 
 		
                        </div>
                </div>		
        </div>
</div>

</body>
</html>
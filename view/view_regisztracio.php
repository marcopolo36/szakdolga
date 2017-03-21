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
					
                                    <h2>Regisztráció</h2>
                                    <form method="post" action="index.php?site=regisztracio">  
                                      Felhasználónév: <input type="text" name="nev">
                                      <br><br>
                                      Jelszó: <input type="password" name="jelszo">
                                      <br><br>
                                      Email cím: <input type="email" name="email">
                                      <br><br>
                                      <input type="checkbox" name="elfogadas" value="igen"><a target="_blank" href="index.php?site=feltetelek"> Felhasználási feltételeket</a> elfogadom.<br>
                                      <br><br>
                                      <input type="submit" name="submit" value="Elküld">
                                      
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
				</div>
			</div>

		
		</div>
	</div>
	
</div>

</body>
</html>
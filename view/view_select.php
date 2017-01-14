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
		
		<div class="col-sm-12" >

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
		
		<!--<p><?php /* echo $page_content; */ ?></p>-->

		<div class="col-sm-12">
			<div class="panel panel-primary">
				<div class="panel-heading">
					<h3 class="panel-title">
						<?php echo $page_main_title; ?>
						<?php
						if (!isset($uzenet)){
							//...
						} else {
							if ($uzenet == 0) {
								?><p>Sikeres törlés</p><?php
							}
							if ($uzenet == 1){
								?><p>Sikertelen törlés</p><?php
							}
							if ($uzenet == 2){
								?><p>Sikeresen visszaállítottam mindenkit aktívvá</p><?php
							}		
							if ($uzenet == 3){
								?><p>Sikertelen volt az aktívvá váltás!</p><?php
							}	
						}
						?>
					</h3>
				</div>
				<div class="panel-body">
					<?php  
					foreach($row as $subarray){
						if($subarray["u_active"] == 1) {
							?><p><?php echo $subarray["u_id"]; ?>&nbsp;<?php
							?><?php echo $subarray["u_name"]; ?></p><?php
							?>
								<form method="post">
									<input type="hidden" name="felhasznalok_id" value="<?php echo $subarray["u_id"]; ?>">
									<input type="hidden" name="site" value="select">
									<input type="hidden" name="action" value="felhasznalok_torol">
									<input type="submit" value="Törlés" />
								</form>
							<?php
						} else {
							?><p>Nincs jogosultságod megtekinteni!</p><?php
						}
					}
					?>
					
					<form method="post">
						<input type="hidden" name="site" value="select">
						<input type="hidden" name="action" value="felhasznalok_visszaallit">
						<input type="submit" value="Felhasználók összes aktívvá változtat" />
					</form>					
					
				</div>
			</div>

		
		</div>
	</div>
	
</div>

</body>
</html>

<!DOCTYPE html>
<html lang="hu">
  <head>
	<title><?php echo $page_title; ?></title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="kviz jatek">
    <meta name="author" content="Divenyi Mark">


    <!-- Bootstrap core CSS -->
    <link href="assets/css/bootstrap.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="assets/css/style.css" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Roboto:300,400,700" rel="stylesheet">
	<script>
    function biztos() {
        if (confirm('Biztosan kijelentkezel?')) {
            window.location = "index.php?site=kijelentkezes";
        }
    }
  </script>
  </head>

  <body>
    <!-- Fixed navbar -->
    <div class="navbar navbar-default navbar-fixed-top" role="navigation">
      <div class="container">
	  <div class="main-menu">
        <div class="navbar-header">
			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Mobil menü</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
        </div>
        <div class="navbar-collapse collapse navbar-center">
          <ul class="nav navbar-nav">
		  
            <?php foreach($menu as $link => $link_text) { ?>
                                                    <?php if($link == "kijelentkezes") { ?>
                                                        <li><a onclick="biztos()" href="#"><?php echo $link_text; ?></a></li>
                                                    <?php } else { ?>
                                                        <li><a href="index.php?site=<?php echo $link; ?>"><?php echo $link_text; ?></a></li>
                                                    <?php } ?>                                                        
                                                <?php } ?>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
	  </div>
    </div>

	
	<!-- *****************************************************************************************************************
	 HEADERWRAP
	 ***************************************************************************************************************** -->
		<div id="headerWrapper">
		<div id="logoimage"></div>
	</div><!-- /headerWrapper -->

	<!-- *****************************************************************************************************************
	 SERVICE LOGOS
	 ***************************************************************************************************************** -->
	 <div id="service">
	 	<div class="container">
 			<div class="row centered">
 				<div class="col-md-2">
 					

 				</div>
 				<div class="col-md-8">
 					 <div id="service2">
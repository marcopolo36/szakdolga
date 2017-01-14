<?php

	$page_title = "Select";
	$menu = array(
		"kezdolap"=>"Kezdőlap", 
		"videok"=>"Videók",
		"admin"=>"Admin",
		"select"=>"Select"
		);
	$page_main_title = "Select oldal!";
	$page_content = "SELECT OLDAL TARTALMA.................";
	
	$servername = "localhost";
	$username = "root";
	$password = "";
	$dbname = "fotolabor";

	$conn = new mysqli($servername, $username, $password, $dbname);

	if ($conn->connect_error) {
		$row = 0;
	} else {
		
		if(isset($_POST["action"])){
			if($_POST["action"] == "felhasznalok_visszaallit"){
					$sql = "UPDATE 
								felhasznalok 
							SET 
								u_active = 1
							";

					if ($conn->query($sql) === TRUE) {
						$uzenet = 2;
					} else {
						$uzenet = 3;
					}			
			}
			if($_POST["action"] == "felhasznalok_torol"){
				if(
					isset($_POST["felhasznalok_id"]) &&
					is_numeric($_POST["felhasznalok_id"])
				){
					$sql = "UPDATE 
								felhasznalok 
							SET 
								u_active = 0
							WHERE
								u_id = ".$_POST["felhasznalok_id"]."";

					if ($conn->query($sql) === TRUE) {
						$uzenet = 0;
					} else {
						$uzenet = 1;
					}
				}
			}
		}
		
		$sql = "SELECT * FROM felhasznalok";
		$result = $conn->query($sql);	
		if ($result->num_rows > 0) {
			while($record = $result->fetch_assoc()) {
				$row[] = $record;
			}
			
		} else {
			$row = 1;
		}	
		$conn->close();		
	}

?>
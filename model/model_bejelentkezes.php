<?php
        function test_input($data) {
          $data = trim($data);
          $data = stripslashes($data);
          $data = htmlspecialchars($data);
          return $data;
        }

        function authentication ($nev, $jelszo){
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "zarodolgozat";

            // Kapcsolat objektum létrehozása
            $conn = new mysqli($servername, $username, $password, $dbname);
            mysqli_set_charset( $conn, 'utf8');
            // Kapcslat ellenőrzése
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            } 

            // SQL utasítás
            $sql = "SELECT * FROM felhasznalo WHERE '".$nev."'=nev AND  '".$jelszo."'=jelszo";

            // SQL utasítás végrehajtása
            $result = $conn->query($sql);
                  
            if ($result->num_rows == 1)
            {
                //$row = $result->fetch_assoc();
                //    echo "<b>" . $row["nev"] . "</b>";
                $_SESSION['felhasznalo_nev']=$nev;
            }else{
                echo "Bejelentkezés sikertelen!";
            }
            
            $conn->close();
        }

	$page_title = "Kezdőlap";
	$menu = array(
		"kezdolap"=>"Kezdőlap", 
		"regisztracio"=>"Regisztráció",
		"uzenetkuldes"=>"Üzenetküldés",
		"kvizjatek"=>"Kvízjáték",
                "kapcsolat"=>"Kapcsolat",
                "bejelentkezes"=>"Bejelentkezés"
		);
	$page_main_title = "Bejelentkezés oldal!";

        $nev = $jelszo = "";
        
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $nev = test_input($_POST["nev"]);
            $jelszo = test_input($_POST["jelszo"]);
            authentication ($nev,$jelszo);
        }
?>
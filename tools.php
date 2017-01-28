<?php

/*
 * Az összes többi fájl által használt funkciók
 */


    function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    function lekerdez ($sql){
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

        // SQL utasítás végrehajtása
        $result = $conn->query($sql);

        $conn->close();

        return $result;
    }
?>

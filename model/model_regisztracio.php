<?php

		checkPermission('regisztracio');

        $reg_sikeres = true;
        $db_iface = new MySQLDatabase();
        $errors = array();
        
        if(isset($_POST['nev'])) { //érkezzen post
            if(empty($_POST['nev'])) {
                $errors[] = 'Üresen hagytad a név mezőt';
            }
            if(empty($_POST['jelszo'])) {
                $errors[] = 'Üresen hagytad a jelszó mezőt';
            }
            if(empty($_POST['email']) || ! filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                $errors[] = 'Üresen hagytad az email mezőt vagy valótlan email címet adtál meg!';
            }
            if(! isset($_POST['elfogadas']) || $_POST['elfogadas'] != 'igen') {
                $errors[] = 'Nem fogadtad el a felhasználási feltételeket!';
            } 
            if(empty($errors) ) {
               $query_string = 'INSERT INTO `{PREFIX}felhasznalo` ' . 
                               '(`nev`,`email`,`jelszo`,`szerepkor_id`) ' .
                               'VALUES ( \'{NEV}\', \'{EMAIL}\',\'{JELSZO}\',\'{SZEREPKOR_ID}\');';
               $params_array =  array ( 'NEV'=>$_POST['nev'],
                                        'EMAIL'=>$_POST['email'],
                                        'JELSZO'=>$_POST['jelszo'],
                                        'SZEREPKOR_ID'=> 1
                                        );
               $result = $db_iface->query($query_string, $params_array);
               
                if(!$result) {
                    $errors[] = $db_iface->report();
                    $reg_sikeres = false;
                } else {
                    login($_POST['nev'],$_POST['jelszo']);
					print "<script type='text/javascript'>".
					      "window.location.href = 'index.php?site=kezdolap';".
						  "</script>";
                }
            }
        }
?>
<?php
include("Mail.php");	// PEAR Mail csomag használata (ilyen nevû fájl ne legyen a script mappájában)
$from = "relaxroomhungary@gmail.com"; // létezõ forrás cím
$to = "relaxroomhungary@gmail.com";   // célcím
$subject = "A levél tárgya";  // a levél tárgya
$body = "Szia!\n\nEz egy tesztüzenet"; // a levél szövege (sortörés: \n)		// gmail.com-hoz
$host = "ssl://smtp.gmail.com" ;		   // smtp kiszolgáló						// $host = "ssl://smtp.gmail.com";
$port = "465"; //8465 can also be used // smtp kiszolgáló komm. portja			// $port = "465"; 
$username = "relaxroomhungary@gmail.com";			   // emailfiókhoz tartozó felhasználnóné	// $username = "e-mailcim";
$password = "relax36polo";			   // emailfiókhoz tartozó jelszó			// $password = "jelszo";
$headers = array ("From" => $from,	   // a levél fejlécét állítjuk elõ
			   "To" => $to,			   // ebben a 
			   "Subject" => $subject); // három sorban
$smtp = Mail::factory("smtp",	array ("host" => $host,			// elõkészítjük a levelet
								   "port" => $port,
								   "auth" => true,				// a kiszolgáló hitelesítést (felhnév, jelszó) igényel
								   "username" => $username,
								   "password" => $password));
$mail = $smtp->send($to, $headers, $body); 						// elküldjük a levelet
if (PEAR::isError($mail)) {										// ha a küldésnél hiba volt
	print("<p>Hibaüzenet: ".$mail->getMessage()."</p>");		// akkor kiírjuk a hibaüzenetet
} else {														// ha nem volt hiba
	print("<p>Levélküldés sikerült.</p>");						// ezt írjuk ki
}
 ?>
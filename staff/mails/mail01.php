<?php
include("Mail.php");	// PEAR Mail csomag haszn�lata (ilyen nev� f�jl ne legyen a script mapp�j�ban)
$from = "relaxroomhungary@gmail.com"; // l�tez� forr�s c�m
$to = "relaxroomhungary@gmail.com";   // c�lc�m
$subject = "A lev�l t�rgya";  // a lev�l t�rgya
$body = "Szia!\n\nEz egy teszt�zenet"; // a lev�l sz�vege (sort�r�s: \n)		// gmail.com-hoz
$host = "ssl://smtp.gmail.com" ;		   // smtp kiszolg�l�						// $host = "ssl://smtp.gmail.com";
$port = "465"; //8465 can also be used // smtp kiszolg�l� komm. portja			// $port = "465"; 
$username = "relaxroomhungary@gmail.com";			   // emailfi�khoz tartoz� felhaszn�ln�n�	// $username = "e-mailcim";
$password = "relax36polo";			   // emailfi�khoz tartoz� jelsz�			// $password = "jelszo";
$headers = array ("From" => $from,	   // a lev�l fejl�c�t �ll�tjuk el�
			   "To" => $to,			   // ebben a 
			   "Subject" => $subject); // h�rom sorban
$smtp = Mail::factory("smtp",	array ("host" => $host,			// el�k�sz�tj�k a levelet
								   "port" => $port,
								   "auth" => true,				// a kiszolg�l� hiteles�t�st (felhn�v, jelsz�) ig�nyel
								   "username" => $username,
								   "password" => $password));
$mail = $smtp->send($to, $headers, $body); 						// elk�ldj�k a levelet
if (PEAR::isError($mail)) {										// ha a k�ld�sn�l hiba volt
	print("<p>Hiba�zenet: ".$mail->getMessage()."</p>");		// akkor ki�rjuk a hiba�zenetet
} else {														// ha nem volt hiba
	print("<p>Lev�lk�ld�s siker�lt.</p>");						// ezt �rjuk ki
}
 ?>
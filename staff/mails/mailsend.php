<?php
include("Mail.php");	// PEAR Mail csomag haszn�lata (ilyen nev� f�jl ne legyen a script mapp�j�ban)
$from = "divenyi.mark@newtrendcom.hu"; // l�tez� forr�s c�m
$to = $_POST["to"];
$subject = $_POST["subject"];
$body = $_POST["text"];
$host = "smtp.upcmail.hu";		   // smtp kiszolg�l�						// $host = "ssl://smtp.gmail.com";
$port = "25"; //8465 can also be used // smtp kiszolg�l� komm. portja			// $port = "465"; 
$username = "divenyi.mark@upcmail.hu";			   // emailfi�khoz tartoz� felhaszn�ln�n�	// $username = "e-mailcim";
$password = "67699444";			   // emailfi�khoz tartoz� jelsz�			// $password = "jelszo";
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
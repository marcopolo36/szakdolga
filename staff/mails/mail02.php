<?
include('Mail.php');
include('Mail/mime.php'); // HTML-hez
$host = "ssl://smtpcorp.com";
$port = "465"; // 8465 can also be used
$username = "smtpuser1234";
$password = "smtppass1234";
$from = "davidxvt@gmail.com";
$to = "davidxvt@gmail.com";     
$subject = "A lev�l t�rgya";
// HTML r�sz kezdete
$text = "Szia!\n\nEz egy teszt�zenet"; // egyszer� sz�veges �zenet
$html = "<html><body><p><b>Szia!</b><br /><br /><i>Ez egy teszt�zenet</i></p></body></html>"; // HTML �zenet
$crlf = "\n"; // sort�r�s karakter megad�sa
$headers = array ("From" => $from,
					"To" => $to,
					"Subject" => $subject);
$mime = new Mail_mime($crlf); // �j MIME objektum
$mime->setTXTBody($text); // egyszer� sz�veg hozz�d�sa
$mime->setHTMLBody($html); // HTML hozz�ad�sa
$body = $mime->get();  // MIME k�dolt lev�lt�rzs megszerz�se
//die($body); // MIME k�dolt lev�lt�rzs ki�r�sa
$headers = $mime->headers($headers); // mime fejl�c be�ll�t�sa
// HTML r�sz v�ge
$smtp = Mail::factory("smtp",	array ("host" => $host,
								   "port" => $port,
								   "auth" => true,
								   "username" => $username,
								   "password" => $password));
$mail = $smtp->send($to, $headers, $body);
if (PEAR::isError($mail)) {
	print("<p>Hiba�zenet: ".$mail->getMessage()."</p>");
} else {
	print("<p>Lev�lk�ld�s siker�lt.</p>");
}
?>
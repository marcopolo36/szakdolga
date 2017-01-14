<?
include('Mail.php');
include('Mail/mime.php'); // HTML-hez
$host = "ssl://smtpcorp.com";
$port = "465"; // 8465 can also be used
$username = "smtpuser1234";
$password = "smtppass1234";
$from = "davidxvt@gmail.com";
$to = "davidxvt@gmail.com";     
$subject = "A levél tárgya";
// HTML rész kezdete
$text = "Szia!\n\nEz egy tesztüzenet"; // egyszerû szöveges üzenet
$html = "<html><body><p><b>Szia!</b><br /><br /><i>Ez egy tesztüzenet</i></p></body></html>"; // HTML üzenet
$crlf = "\n"; // sortörés karakter megadása
$headers = array ("From" => $from,
					"To" => $to,
					"Subject" => $subject);
$mime = new Mail_mime($crlf); // új MIME objektum
$mime->setTXTBody($text); // egyszerû szöveg hozzádása
$mime->setHTMLBody($html); // HTML hozzáadása
$body = $mime->get();  // MIME kódolt levéltörzs megszerzése
//die($body); // MIME kódolt levéltörzs kiírása
$headers = $mime->headers($headers); // mime fejléc beállítása
// HTML rész vége
$smtp = Mail::factory("smtp",	array ("host" => $host,
								   "port" => $port,
								   "auth" => true,
								   "username" => $username,
								   "password" => $password));
$mail = $smtp->send($to, $headers, $body);
if (PEAR::isError($mail)) {
	print("<p>Hibaüzenet: ".$mail->getMessage()."</p>");
} else {
	print("<p>Levélküldés sikerült.</p>");
}
?>
<?
include('Mail.php');
include('Mail/mime.php');
$host = "ssl://smtpcorp.com";
$port = "465"; // 8465 can also be used
$username = "smtpuser1234";
$password = "smtppass1234";
$from = "davidxvt@gmail.com";
$to = "davidxvt@gmail.com";     
$subject = "A lev�l t�rgya";
$text = "Szia!\n\nEz egy teszt�zenet";
$html = "<html><body><p><b>Szia!</b><br /><br /><i>Ez egy teszt�zenet</i></p></body></html>";
$crlf = "\n";
$headers = array ("From" => $from,
					"To" => $to,
					"Subject" => $subject);
$mime = new Mail_mime($crlf);
$mime->setTXTBody($text);
$mime->setHTMLBody($html);
// mail02.php kieg�sz�t�s: csatol�s kezdete (MINDENK�PP A $mime->get() el�tt kell lennie)
$mime->addAttachment("mail01.txt"); // mail01.txt csatol�sa
$mime->addAttachment("test.docx");  // test.docx csatol�s
// mail02.php kieg�sz�t�s: csatol�s v�ge
$body = $mime->get();
//die($body);
$headers = $mime->headers($headers);
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
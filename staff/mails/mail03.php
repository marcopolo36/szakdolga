<?
include('Mail.php');
include('Mail/mime.php');
$host = "ssl://smtpcorp.com";
$port = "465"; // 8465 can also be used
$username = "smtpuser1234";
$password = "smtppass1234";
$from = "davidxvt@gmail.com";
$to = "davidxvt@gmail.com";     
$subject = "A levél tárgya";
$text = "Szia!\n\nEz egy tesztüzenet";
$html = "<html><body><p><b>Szia!</b><br /><br /><i>Ez egy tesztüzenet</i></p></body></html>";
$crlf = "\n";
$headers = array ("From" => $from,
					"To" => $to,
					"Subject" => $subject);
$mime = new Mail_mime($crlf);
$mime->setTXTBody($text);
$mime->setHTMLBody($html);
// mail02.php kiegészítés: csatolás kezdete (MINDENKÉPP A $mime->get() elõtt kell lennie)
$mime->addAttachment("mail01.txt"); // mail01.txt csatolása
$mime->addAttachment("test.docx");  // test.docx csatolás
// mail02.php kiegészítés: csatolás vége
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
	print("<p>Hibaüzenet: ".$mail->getMessage()."</p>");
} else {
	print("<p>Levélküldés sikerült.</p>");
}
?>
<?
//print($_POST["to"]);
//print($_POST["subject"]);
//print($_POST["text"]);
include('Mail.php');
include('Mail/mime.php');
$host = "ssl://smtp.gmail.com";
$port = "465"; // 8465 can also be used
$username = "relaxroomhungary@gmail.com";
$password = "relax36polo";
$from = "relaxroomhungary@gmail.com";
$to = $_POST["to"];   
$subject = $_POST["subject"];
$text = $_POST["text"];
$html = "<html><body>".$_POST["text"]."</body></html>";
$crlf = "\n";
$headers = array ("From" => $from,
					"To" => $to,
					"Subject" => $subject);
$mime = new Mail_mime($crlf);
$mime->setTXTBody($text);
$mime->setHTMLBody($html);

// �rlapr�l �rkez� f�jl becsatol�sa kezdete
$forras=$_FILES["csatolt"]["tmp_name"];	// �rlapr�l �rkez� f�jl ideiglenes helye
$cel="./".$_FILES["csatolt"]["name"];   // ide m�soljuk (aktu�lis mapp�ba, eredeti n�ven)
move_uploaded_file($forras,$cel);       // mozgat�s elv�gz�se
$mime->addAttachment($_FILES["csatolt"]["name"]);  // hozz�f�z�s a lev�lhez
// �rlapr�l �rkez� f�jl becsatol�sa v�ge

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
@unlink("./".$_FILES["csatolt"]["name"]); // a felt�lt�tt �s a m�r - rem�lhet�leg - elk�ld�tt f�jl t�rl�se
?>
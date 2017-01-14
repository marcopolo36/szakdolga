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

// ûrlapról érkezõ fájl becsatolása kezdete
$forras=$_FILES["csatolt"]["tmp_name"];	// ûrlapról érkezõ fájl ideiglenes helye
$cel="./".$_FILES["csatolt"]["name"];   // ide másoljuk (aktuális mappába, eredeti néven)
move_uploaded_file($forras,$cel);       // mozgatás elvégzése
$mime->addAttachment($_FILES["csatolt"]["name"]);  // hozzáfûzés a levélhez
// ûrlapról érkezõ fájl becsatolása vége

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
@unlink("./".$_FILES["csatolt"]["name"]); // a feltöltött és a már - remélhetõleg - elküldött fájl törlése
?>
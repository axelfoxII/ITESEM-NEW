<?PHP
include_once dirname(__FILE__)."/qrlib.php";
 
// --- url
$url = "david avalos severiche";
//$url = "https://es.wikipedia.org";
 
	$ci='987654';
QRcode::png($url);
QRcode::png($url,"temp/$ci.png",QR_ECLEVEL_L,3,1);

echo '<img src="temp/$ci.png"/>';

?>
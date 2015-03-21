<?php include '../common/db.php'; ?>
<?PHP
header("Content-Type:text/plain; charset=UTF-8"); 
header("Cache-Control:no-cache");
header("Pragma:no-cache");

$appid = isset($_POST["appid"]) ? $_POST["appid"] : '';
$macaddr = isset($_POST["macaddr"]) ? $_POST["macaddr"] : '';
$cliip = isset($_POST["cliip"]) ? $_POST["cliip"] : '';
$releaseno = isset($_POST["releaseno"]) ? $_POST["releaseno"] : '';


$ok = $db->open();
$result = '';
$pathimage = '';

if ($ok) {
  $str = "SELECT idrelease, pathimage FROM t_release where imgtype = 1 and applydate <= now() order by applydate desc, idrelease limit 0, 1;";
	switch ($db->querySelect($str)) {
  case 1:
    $result = 'OK';
 		$row = $db->goNext();;
		$releaseno = $row['idrelease'];
		$pathimage = $row['pathimage'];
    break;
  case 0:
    $result = 'NOT';
    break;
  default:
    $result = 'ERR';
    break;
  }
	$db->free();
  
	$db->close();
}

echo $result."\r\n";
echo $releaseno."\r\n";
echo $pathimage."\r\n";
?>

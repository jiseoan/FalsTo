<?php include '../common/db.php'; ?>
<?PHP
header("Content-Type:text/plain; charset=UTF-8"); 
header("Cache-Control:no-cache");
header("Pragma:no-cache");

$idclient = 0;
$appid = isset($_POST["appid"]) ? $_POST["appid"] : '';
$macaddr = isset($_POST["macaddr"]) ? $_POST["macaddr"] : '';
$cliip = isset($_POST["cliip"]) ? trim($_POST["cliip"]) : '';
$alive = isset($_POST["alive"]) ? $_POST["alive"] : '';

$ok = $db->open();

if ($ok && strlen($alive) > 0) {
  $str = "update t_client set alivetext = '".$alive."', alivetime = now() where macaddr = '".$macaddr."';";
  $db->query($str);
	$db->close();
}

echo "OK";

?>

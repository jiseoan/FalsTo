<?php include '../common/db.php'; ?>
<?PHP
header("Content-Type:text/plain; charset=UTF-8"); 
header("Cache-Control:no-cache");
header("Pragma:no-cache");

$appid = isset($_POST["appid"]) ? $_POST["appid"] : '';
$macaddr = isset($_POST["macaddr"]) ? $_POST["macaddr"] : '';
$cliip = isset($_POST["cliip"]) ? $_POST["cliip"] : '';

$result = 'ERR';

$ok = $db->open();

if ($ok) {
  date_default_timezone_set('Asia/Seoul');
  $cur = time();
  
	$str = "select count(*) as cnt from t_client where macaddr = '".$macaddr."' and reboot is not null and addtime(reboot, '0:2:0') >= now();";
	$n = $db->queryCount($str, "cnt");
  
	$str = "UPDATE t_client set reboot = null where macaddr = '".$macaddr."';";
	$db->query($str);
  
	if ($n == 1) {
    $result = 'OK';
	}
  else {
    $result = 'NOT';
  }

	$db->close();
}

echo $result;
?>

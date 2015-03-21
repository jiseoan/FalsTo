<?php include '../cli/configure.php'; ?>
<?PHP
$appid = isset($_GET["appid"]) ? $_GET["appid"] : (isset($_POST["appid"]) ? $_POST["appid"] : '');
$configure = '';

$ok = $db->open();

if ($ok) {
	$str = "select position from t_client where name = '".$appid."';";

	$n = $db->querySelect($str);
	if ($n > 0) {
		$row = $db->goNext();
		$pos = $row["position"];
		$db->free();
    
		$configure = GetConfigureJson($db, $appid, $pos);
	}
  
	$db->close();
}

echo $configure;
?>

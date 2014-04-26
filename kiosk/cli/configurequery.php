<?php include 'configure.php'; ?>
<?PHP
header("Content-Type:text/plain; charset=UTF-8"); 
header("Cache-Control:no-cache");
header("Pragma:no-cache");

$appid = isset($_POST["appid"]) ? $_POST["appid"] : '';
$macaddr = isset($_POST["macaddr"]) ? $_POST["macaddr"] : '';
$cliip = isset($_POST["cliip"]) ? $_POST["cliip"] : '';

$configure = '';

$ok = $db->open();

if ($ok) {
  $str = "select name, position from t_client where macaddr = '".$macaddr."';";
  $n = $db->querySelect($str);
  if ($n > 0) {
    $row = $db->goNext();
    $cliname = $row["name"];
    $pos = $row["position"];
    $db->free();
    
    $configure = GetConfigureJson($db, $cliname, $pos);
  }
  
	$db->close();
}

echo (strlen($configure) > 0 ? "OK" : "ERR")."\r\n";
echo $cliname."\r\n";
echo $configure;


?>

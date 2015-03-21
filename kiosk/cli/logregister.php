<?php include '../common/db.php'; ?>
<?PHP
header("Content-Type:text/plain; charset=UTF-8"); 
header("Cache-Control:no-cache");
header("Pragma:no-cache");

$idclient = 0;
$appid = isset($_POST["appid"]) ? $_POST["appid"] : '';
$macaddr = isset($_POST["macaddr"]) ? $_POST["macaddr"] : '';
$cliip = isset($_POST["cliip"]) ? trim($_POST["cliip"]) : '';
$major = isset($_POST["major"]) ? intval($_POST["major"]) : 0;
$minor = isset($_POST["minor"]) ? intval($_POST["minor"]) : 0;
$more = isset($_POST["more"]) ? trim($_POST["more"]) : '';
if ($more == null) {
  $more = '';
 }


$ok = $db->open();

if ($ok) {
  $str = "select idclient from t_client where macaddr = '".$macaddr."';";
  $n = $db->querySelect($str);
  if ($n == 0) {
	  $str = "INSERT INTO t_client (name, position, macaddr, cliip4, dalogtime) VALUES ('새키오스크(".$cliip.")', 'west;1;0;0', '".$macaddr."', '".$cliip."', now());";
    $db->query($str);
    $str = "SELECT LAST_INSERT_ID() as idclient;";
    $idclient = $db->queryCount($str, "idclient");
  }
  else {
    $row = $db->goNext();;
    $idclient = $row['idclient'];
  }
  
  // 로그 저장
	$str = "INSERT INTO t_clientlog (idclient, cliip4, major, minor, more) VALUES (".$idclient.", '".$cliip."', ".$major.", ".$minor.", '".$more."');";
  $db->query($str);
  
  // 클라이언트 상태 적용
  if (($major == 1 && $minor == 1) || ($major == 2 && $minor == 2)) {
	  $str = "update t_client set state = 'D', alivetime = null, dalogtime = now() where idclient = ".$idclient.";";
  }
  else {
	  $str = "update t_client set state = 'A', alivetime = ".(($major == 3 && ($minor == 2 || $minor == 4)) ? "null" : "now()").", dalogtime = now() where idclient = ".$idclient.";";
  }
  $db->query($str);

	$db->close();
}



echo "OK";


?>

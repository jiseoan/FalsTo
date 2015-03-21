<?php include '../common/db.php'; ?>
<?PHP
header("Content-Type:text/plain; charset=UTF-8"); 
header("Cache-Control:no-cache");
header("Pragma:no-cache");

$appid = isset($_POST["appid"]) ? $_POST["appid"] : '';
$macaddr = isset($_POST["macaddr"]) ? $_POST["macaddr"] : '';
$cliip = isset($_POST["cliip"]) ? $_POST["cliip"] : '';

$colname = array("holisun", "holimon", "holitue", "holiwed", "holithr", "holifri", "holisat");
$result = 'ERR';

$ok = $db->open();

if ($ok) {
  date_default_timezone_set('Asia/Seoul');
  $cur = time();
  
	$str = "SELECT ".$colname[date("w", $cur)]." as dayofweek, hourbegin, hourend FROM t_operating where enable = 'Y' order by idoperating desc limit 0, 1;";
	$n = $db->querySelect($str);
	if ($n == 1) {
		$row = $db->goNext();
    
    if ($row['dayofweek'] == 'Y' || $cur < strtotime($row['hourbegin']) || $cur >= strtotime($row['hourend'])) {
      $result = 'OK';
    }
    else {
      $result = 'NOT';
    }
	}
  else {
    $result = 'NOT';
  }

	$db->close();
}


echo $result;


?>

<?php
$compath = '/common/';
if (isset($_SERVER["DOCUMENT_ROOT"]) && strlen($_SERVER["DOCUMENT_ROOT"]) > 0) {
  $compath = '..'.$compath;
}
else {
  $compath = dirname(dirname(__FILE__))."/".$compath;
}

include $compath.'db.php';
include $compath.'wol.php';
?>
<?php
$colname = array("holisun", "holimon", "holitue", "holiwed", "holithr", "holifri", "holisat");
$logfile = isset($_GET["logfile"]) ? $_GET["logfile"] : '';
$result = 'NOT';

$ok = $db->open();

if ($ok) {
	date_default_timezone_set('Asia/Seoul');
  
	$fh = false;
	if (strlen($logfile) > 0) {
		$fh = fopen($logfile, "a");
		if ($fh) {
			fwrite($fh, "======= ".date("Y-m-d H:i:s")."=======\r\n");
		}
	}
  
	$interval = 360; // 6분
	$cur = time();
	$str = "SELECT ".$colname[date("w", $cur)]." as dayofweek, hourbegin FROM t_operating where enable = 'Y' order by idoperating desc limit 0, 1;";
	$n = $db->querySelect($str);
	if ($n == 1) {
		$row = $db->goNext();
    $begin = strtotime($row['hourbegin']);
    $end = $begin + $interval;
		if ($row['dayofweek'] != 'Y' && $cur >= $begin && $cur <= $end) {
		  $result = 'OK';
		}
	}

	if ($fh) {
		fwrite($fh, $result."\r\n");
	}

	if ($result == 'OK') {
		$str = "select distinct macaddr from t_client;";
		$n = $db->querySelect($str);
    
		for ($i = 0 ; $i < $n ; $i++)
		{
			$row = $db->goNext();
			$macaddr = $row['macaddr'];
			$wol->wakeOnLan($macaddr);
			$b = ($wol->getLastStatus() == "OK");

			if ($fh) {
				fwrite($fh, $macaddr." -> ".($b ? "OK" : "ERR")."\r\n");
			}
		}

		$db->free();
	}

	if ($fh) {
		fclose($fh);
	}
	
	$db->close();
}

echo $result;
?>

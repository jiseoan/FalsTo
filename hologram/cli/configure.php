<?php include '../common/db.php'; ?>
<?PHP
function GetConfigureJson($db, $cliname, $pos) {
	$str = "SELECT scrsaverintv, updateintv, showtime FROM t_operating where enable = 'Y' order by idoperating desc limit 0, 1;";
	$n = $db->querySelect($str);
	if ($n == 1) {
		$row = $db->goNext();
    
    $outarr = array("appid"=>$cliname,
                    "updateInterval"=>(int)$row['updateintv'],
                    "screensaverInterval"=>(int)$row['scrsaverintv'],
                    "showtime"=>(int)$row['showtime']);
    
    $db->free();
    
    $str = json_encode($outarr, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
		return $str;
	}
  
  return '';
}
?>

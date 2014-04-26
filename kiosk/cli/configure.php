<?php include '../common/db.php'; ?>
<?PHP
function GetConfigureJson($db, $cliname, $pos) {
	$str = "SELECT scrsaverintv, updateintv, rollheader, rollimgslider, rollshopinfoticker, rollranking FROM t_operating where enable = 'Y' order by idoperating desc limit 0, 1;";
	$n = $db->querySelect($str);
	if ($n == 1) {
		$row = $db->goNext();;
    
    $posarr = explode(";", $pos);
    $posarr2 = array("x"=>(count($posarr) >= 3 ? (int)$posarr[2] : 0), "y"=>(count($posarr) >= 4 ? (int)$posarr[3] : 0));
    
    $rollarr = array("header"=>(int)$row['rollheader'], "imageSlider"=>(int)$row['rollimgslider'],
                     "shoppingInfoTicker"=>(int)$row['rollshopinfoticker'], "ranking"=>(int)$row['rollranking']);
    
    
    $outarr = array("appid"=>$cliname, "floor"=>(count($posarr) >= 2 ? (int)$posarr[1] : 0), "hall"=>$posarr[0],
                 "position"=>$posarr2, "updateInterval"=>(int)$row['updateintv'], "screensaverInterval"=>(int)$row['scrsaverintv'],
                 "rollingInterval"=>$rollarr);
    
    $db->free();
    
    $str = json_encode($outarr, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
  	return $str;
	}
  
  return '';
}
?>

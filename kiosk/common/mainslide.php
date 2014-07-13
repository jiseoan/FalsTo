<?php
function getMainSlideJsondata($base_URL, $urlenc, $db) {
	$imgs = array();
  $str = "select name from t_mainslide order by seqno, name";
	$n = $db->querySelect($str);
    
	for ($i = 0 ; $i < $n ; $i++) {
		$row = $db->goNext();
		$imgs[$i] = $base_URL.($urlenc ? dirname($row['name'])."/".urlencode(basename($row['name'])) : $row['name']);
	}
	$db->free();
  
  $outarr = array("images"=>$imgs);
  $outstr = json_encode($outarr, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT);
  
  return $outstr;
}
?>

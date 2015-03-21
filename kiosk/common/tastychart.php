<?php
function getTastyChartJsondata($base_URL, $urlenc, $db) {
  date_default_timezone_set('Asia/Seoul');
  $year = date("Y");
  $month = date("n");
  
  $lang = array("kor", "eng", "jpn", "chn");
	$charts = array();
  $str = "select year, month, idlang, pathimage from t_tastychart where (year < ".$year.") or (year = ".$year." and month < ".$month.") order by year desc, month desc, idlang";
	$n = $db->querySelect($str);
    
	for ($i = 0, $j = 0 ; $i < $n ; $j++) {
    if ($i == 0) {
  		$row = $db->goNext();
    }
    
    $year = $row['year'];
    $month = $row['month'];
		$charts[$j] = array();
		$charts[$j]['year'] = $year;
		$charts[$j]['month'] = $month;
    
    $image = array();
    $nmlang = $lang[$row['idlang'] - 1];
    $image[$nmlang] = strlen($row['pathimage']) > 0 ? $base_URL.($urlenc ? dirname($row['pathimage'])."/".urlencode(basename($row['pathimage'])) : $row['pathimage']) : "";
    
    while (++$i < $n) {
      $row = $db->goNext();
      if ($year != $row['year'] || $month != $row['month']) {
        break;
      }
      $nmlang = $lang[$row['idlang'] - 1];
      $image[$nmlang] = strlen($row['pathimage']) > 0 ? $base_URL.($urlenc ? dirname($row['pathimage'])."/".urlencode(basename($row['pathimage'])) : $row['pathimage']) : "";
    }
    
		$charts[$j]['image'] = $image;
	}
	$db->free();
  
  $outarr = array("items"=>$charts);
  $outstr = json_encode($outarr, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT);
  
  return $outstr;
}
?>

<?php
function getOnlyGelleriaJsondata($base_URL, $urlenc, $db) {
  $lang = array("kor", "eng", "jpn", "chn");
  $items = array();
  $str = "select idonlygalleria, idlang, paththumb, pathtextimg, pathimg from t_onlygalleria order by seqno, idonlygalleria, idlang";
  $n = $db->querySelect($str);
  
	for ($i = 0, $j = 0 ; $i < $n ; $j++) {
    if ($i == 0) {
  		$row = $db->goNext();
    }
    
    $idonlygalleria = $row['idonlygalleria'];
		$items[$j] = array();
    
    $thumbnail = array();
    $text = array();
    $image = array();
    
    $nmlang = $lang[$row['idlang'] - 1];
    $thumbnail[$nmlang] = strlen($row['paththumb']) > 0 ? $base_URL.($urlenc ? dirname($row['paththumb'])."/".urlencode(basename($row['paththumb'])) : $row['paththumb']) : "";
    $text[$nmlang] = strlen($row['pathtextimg']) > 0 ? $base_URL.($urlenc ? dirname($row['pathtextimg'])."/".urlencode(basename($row['pathtextimg'])) : $row['pathtextimg']) : "";
    $image[$nmlang] = strlen($row['pathimg']) > 0 ? $base_URL.($urlenc ? dirname($row['pathimg'])."/".urlencode(basename($row['pathimg'])) : $row['pathimg']) : "";
    
    while (++$i < $n) {
      $row = $db->goNext();
      if ($idonlygalleria != $row['idonlygalleria']) {
        break;
      }
      $nmlang = $lang[$row['idlang'] - 1];
      $thumbnail[$nmlang] = strlen($row['paththumb']) > 0 ? $base_URL.($urlenc ? dirname($row['paththumb'])."/".urlencode(basename($row['paththumb'])) : $row['paththumb']) : "";
      $text[$nmlang] = strlen($row['pathtextimg']) > 0 ? $base_URL.($urlenc ? dirname($row['pathtextimg'])."/".urlencode(basename($row['pathtextimg'])) : $row['pathtextimg']) : "";
      $image[$nmlang] = strlen($row['pathimg']) > 0 ? $base_URL.($urlenc ? dirname($row['pathimg'])."/".urlencode(basename($row['pathimg'])) : $row['pathimg']) : "";
    }
    
		$items[$j]['thumbnail'] = $thumbnail;
		$items[$j]['text'] = $text;
		$items[$j]['image'] = $image;
	}
	$db->free();
  
  $outarr = array("items"=>$items);
  $outstr = json_encode($outarr, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT);
  
  return $outstr;
}
?>

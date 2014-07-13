<?php
function getShoppingInfoJsondata($base_URL, $urlenc, $db) {
	$infos = array();
  $str = "select idshopinfo, tmpltype, site, postbegin, postend, thumbnail from t_shoppinginfo order by seqno, idshopinfo desc, postbegin desc, postend desc";
	$n = $db->querySelect($str);
    
	for ($i = 0 ; $i < $n ; $i++) {
		$row = $db->goNext();
		$infos[$i] = array();
		$infos[$i]['idshopinfo'] = $row['idshopinfo'];
		$infos[$i]['template-type'] = $row['tmpltype'];
		$infos[$i]['site'] = $row['site'];
		$infos[$i]['post-begin'] = $row['postbegin'];
		$infos[$i]['post-end'] = $row['postend'];
		$infos[$i]['thumbnail'] = $base_URL.($urlenc ? dirname($row['thumbnail'])."/".urlencode(basename($row['thumbnail'])) : $row['thumbnail']);
	}
	$db->free();
  
  $lang = array("kor", "eng", "jpn", "chn");
  $nimg = array(0, 1, 6);
  
	for ($i = 0 ; $i < $n ; $i++) {
    $infos[$i]['title'] = array();
    $infos[$i]['desc'] = array();
    $infos[$i]['image-desc'] = array();
    $infos[$i]['target'] = array();
    $infos[$i]['period'] = array();
    $infos[$i]['location'] = array();
    $infos[$i]['etc-desc'] = array();
    $infos[$i]['ticker'] = array();
      
    $str = "select idlang, title, descinfo, descimg, target, period, location, descetc, ticker from t_shoppinginfoext where idshopinfo = ".$infos[$i]['idshopinfo']." order by idlang";
		$m = $db->querySelect($str);
    
		for ($j = 0 ; $j < $m ; $j++) {
			$row = $db->goNext();
      $nmlang = $lang[$j];
      $infos[$i]['title'][$nmlang] = stripslashes($row['title']);
      $infos[$i]['desc'][$nmlang] = stripslashes($row['descinfo']);
      $infos[$i]['image-desc'][$nmlang] = stripslashes($row['descimg']);
      $infos[$i]['target'][$nmlang] = stripslashes($row['target']);
      $infos[$i]['period'][$nmlang] = stripslashes($row['period']);
      $infos[$i]['location'][$nmlang] = stripslashes($row['location']);
      $infos[$i]['etc-desc'][$nmlang] = stripslashes($row['descetc']);
      $infos[$i]['ticker'][$nmlang] = stripslashes($row['ticker']);
		}
		$db->free();
    
    $images = array();
    for ($j = 0 ; $j < count($lang) ; $j++) {
      $imgarr = array();
      $str = "select ifnull(path, '') as path from t_shoppinginfoimage where idshopinfo = ".$infos[$i]['idshopinfo']." and idlang = ".($j + 1)." order by seq";
		  $m = $db->querySelect($str);
      
		  for ($k = 0, $l = 0 ; $k < $m ; $k++) {
			  $row = $db->goNext();
        if (strlen($row['path']) > 0) {
          $imgarr[$l++] = $base_URL.($urlenc ? dirname($row['path'])."/".urlencode(basename($row['path'])) : $row['path']);
        }
		  }
		  $db->free();
      
      $images[$lang[$j]] = $imgarr;
    }
    
    $infos[$i]['images'] = $images;
    
    /* 예전의 인덱스 중심처리 코드
    $str = "select path from t_shoppinginfoimage where idshopinfo = ".$infos[$i]['idshopinfo']." order by seq, idlang";
		$m = $db->querySelect($str);
    
		for ($j = 0 ; $j < $m ; $j++) {
			$row = $db->goNext();
      $seq = (int)($j / 4) + 1;
      $nmimg = "image-".$seq;
      $nmlang = $lang[$j % 4];
      
      if (strlen($row['path']) > 0) {
        $infos[$i][$nmimg][$nmlang] = $base_URL.($urlenc ? dirname($row['path'])."/".urlencode(basename($row['path'])) : $row['path']);
      }
      else {
        $infos[$i][$nmimg][$nmlang] = "";
      }
		}
		$db->free();
    */
	}
  
  $outarr = array("items"=>$infos);
  $outstr = json_encode($outarr, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT);
  
  return $outstr;
}
?>

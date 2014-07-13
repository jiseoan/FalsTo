<?php
function getBrandJsondata($base_URL, $urlenc, $db) {
  $dftLang = 1;	// 한국어
  $idgrpcateg = 1;	// 브랜드분류
	$items = array();
  $str = "SELECT i.iditem, ifnull(g.name,'') as category, ifnull(pathimage,'') as pathimage, ifnull(hall,'') as hall, ifnull(floor,'') as floor, xpos, ypos, ifnull(phone,'') as phone, ifnull(tag,'') as tag FROM t_item as i inner join t_grp as g on (g.idgrpcateg = ".$idgrpcateg." and i.idgrp = g.idgrp and g.idlang = ".$dftLang.") order by g.seq, g.idgrp desc, i.iditem desc;";
  $n = $db->querySelect($str);
		
  for ($i = 0 ; $i < $n ; $i++) {
	  $row = $db->goNext();
	  $items[$i] = array();
	  $items[$i]['iditem'] = $row['iditem'];
	  $items[$i]["category"] = $row['category'];
	  $items[$i]["name"] = array();
	  $items[$i]["description"] = array();
	  $items[$i]["hall"] = $row['hall'];
	  $items[$i]["floor"] = $row['floor'];
	  $items[$i]["phone"] = $row['phone'];
	  $items[$i]["position"] = array("x"=>$row['xpos'],"y"=>$row['ypos']);
    if (strlen($row['pathimage']) > 0) {
      $items[$i]["image"] = $base_URL.($urlenc ? dirname($row['pathimage'])."/".urlencode(basename($row['pathimage'])) : $row['pathimage']);
    }
    else {
      $items[$i]["image"] = "";
    }
	  $items[$i]["tag"] = stripslashes($row['tag']);
  }
  $db->free();
  
  $lang = array("kor", "eng", "jpn", "chn");
    
	for ($i = 0 ; $i < $n ; $i++) {
    $str = "select ifnull(attrval,'') as name FROM t_itemext where iditem = ".$items[$i]['iditem']." and idattr = 'name' order by idlang";
		$m = $db->querySelect($str);
    
		for ($j = 0 ; $j < $m ; $j++) {
			$row = $db->goNext();
      $nmlang = $lang[$j];
      $items[$i]['name'][$nmlang] = stripslashes($row['name']);
		}
		$db->free();
      
    $str = "select ifnull(attrval,'') as description FROM t_itemext where iditem = ".$items[$i]['iditem']." and idattr = 'description' order by idlang";
		$m = $db->querySelect($str);
    
		for ($j = 0 ; $j < $m ; $j++) {
			$row = $db->goNext();
      $nmlang = $lang[$j];
      $items[$i]['description'][$nmlang] = stripslashes($row['description']);
		}
		$db->free();
	}
  
  $outarr = array("items"=>$items);
  $outstr = json_encode($outarr, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT);
  
  return $outstr;
}
?>

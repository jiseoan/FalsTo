<?php
function getFoodMenuJsondata($base_URL, $urlenc, $db) {
	$items = array();
  
	$str = "SELECT idgrp FROM t_grp where name = 'FOODMENU';";
	$idgrp = $db->queryCount($str, "idgrp");
  
  $str = "SELECT iditem, idparentitem, ifnull(pathimage,'') as pathimage, tag, price FROM t_item  where idgrp = ".$idgrp;
  $n = $db->querySelect($str);
		
  for ($i = 0 ; $i < $n ; $i++) {
	  $row = $db->goNext();
	  $items[$i] = array();
	  $items[$i]['iditem'] = $row['iditem'];
	  $items[$i]['idbrand'] = $row['idparentitem'];
	  $items[$i]["name"] = array();
	  $items[$i]["description"] = array();
    if (strlen($row['pathimage']) > 0) {
      $items[$i]["image"] = $base_URL.($urlenc ? dirname($row['pathimage'])."/".urlencode(basename($row['pathimage'])) : $row['pathimage']);
    }
    else {
      $items[$i]["image"] = "";
    }
	  $items[$i]["tag"] = stripslashes($row['tag']);
	  $items[$i]["price"] = stripslashes($row['price']);
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

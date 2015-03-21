<?php
function getJsondata($isWeb, $db) {
  $nameLangID = 2;
  $base_URL = $isWeb ? "/hologram/" : "./";
  $imgDir = "upimg/";
  $modelDir = "upmodel/";
  $imgDir2 = $isWeb ? $imgDir : "img/";
  $modelDir2 = $isWeb ? $modelDir : "model/";
  
  /* 초성 삭제
                  // 이름, 초성, 특징, 국적/시대, 재질, 크기, 소장기관, 지정구분, 유물번호, 설명이미지
  $dspattrs = array("fullname", "consonant", "feature", "nationera", "material", "size", "institution", "designation", "relicnumber", "imgdesc");
  $idxdspattrs = array("fullname"=>0, "consonant"=>1, "feature"=>2, "nationera"=>3, "material"=>4, "size"=>5, "institution"=>6, "designation"=>7, "relicnumber"=>8, "imgdesc"=>9);
  */
  
                  // 이름, 특징, 국적/시대, 재질, 크기, 소장기관, 지정구분, 유물번호, 설명이미지
  $dspattrs = array("fullname", "feature", "nationera", "material", "size", "institution", "designation", "relicnumber", "imgdesc");
  $idxdspattrs = array("fullname"=>0, "feature"=>1, "nationera"=>2, "material"=>3, "size"=>4, "institution"=>5, "designation"=>6, "relicnumber"=>7, "imgdesc"=>8);
  
  $ndspattrs = count($dspattrs);
	$idlangs = array();
  $nmlangs = array();
  $ennmlangs = array();
  
  // 언어
  $arrLang = array();
	$str = "SELECT l.idlang, name, enname, ifnull(consonantseq, '') as conseq, menuimage FROM t_lang as l left join t_envlang as e on (e.idlang = l.idlang) where LENGTH(menuimage) > 0;";
	$nidlangs = $db->querySelect($str);
	for ($i = 0 ; $i < $nidlangs ; $i++) {
		$row = $db->goNext();
	  $idlangs[$i] = $row['idlang'];
    $nmlangs[$i] = $row['name'];
    $ennmlangs[$i] = $row['enname'];
      
    /* 초성 삭제됨
    $arr = array();
    $str = $row['conseq'];
    for ($j = 0, $m = mb_strlen($str, 'UTF-8') ; $j < $m ; $j++) {
      $arr[$j] = mb_substr($str, $j, 1, 'UTF-8');
    }
    $arrLang[$i] = array("name"=>$row['enname'], "order"=>$arr, "image"=>$base_URL.$row['menuimage']);
    */
      
    $arrLang[$i] = array("name"=>$row['enname'], "image"=>$base_URL.str_replace($imgDir, $imgDir2, $row['menuimage']));
	}
  $db->free();
  
  // 카테고리
  $arrGroup = array();
  $idgrps = array();
  $nmgrps = array();
	for ($i = 0 ; $i < $nidlangs ; $i++) {
    $idgrps[$i] = array();
    $nmgrps[$i] = array();
	  $str = "SELECT idgrp, name FROM t_grp where idgrpcateg = 1 and idlang = ".$idlangs[$i]." order by seq;";
	  $n = $db->querySelect($str);
	  for ($j = 0 ; $j < $n ; $j++) {
		  $row = $db->goNext();
	    $idgrps[$i][$j] = $row['idgrp'];
      $nmgrps[$i][$j] = $row['name'];
	  }
    $db->free();
    
    $arrGroup[$i] = array("name"=>$ennmlangs[$i], "order"=>$nmgrps[$i]);
	}
  
  $attrnams = array();
  // 언어별 속성이름
	for ($i = 0 ; $i < $nidlangs ; $i++) {
    $attrnams[$i] = array();
	  for ($j = 0 ; $j < $ndspattrs ; $j++) {
      $str = "SELECT name FROM t_itemattr where idattr = '".$dspattrs[$j]."' and idlang = ".$idlangs[$i].";";
      $n = $db->querySelect($str);
      if ($n == 1) {
        $row = $db->goNext();;
        $attrnams[$i][$j] = $row['name'];
      }
      else {
        $attrnams[$i][$j] = '';
      }
      $db->free();
	  }
	}
  
  // 아이템리스트
  $arrItems = array();
	$iditems = array();
	$idgrps2 = array();
  $nmitems = array();
  $models = array();
  $images = array();
  
	$str = "SELECT i.iditem, idgrp, e.attrval as name, ifnull(path3ddata,'') as model, ifnull(paththumbnail,'') as image FROM t_item as i left join t_itemext as e on (e.iditem = i.iditem and e.idlang = ".$nameLangID." and e.idattr = 'fullname') order by iditem desc;";
	$niditems = $db->querySelect($str);
	for ($i = 0 ; $i < $niditems ; $i++) {
		$row = $db->goNext();
	  $iditems[$i] = $row['iditem'];
	  $idgrps2[$i] = $row['idgrp'];
    $nmitems[$i] = $row['name'];
    $models[$i] = $row['model'];
    $images[$i] = $row['image'];
	}
  $db->free();
  
  for ($i = 0 ; $i < $niditems ; $i++) {
    $arrDesc = array();
    $attrvals = array();
    $konmgrp = '';
    
	  for ($j = 0 ; $j < $nidlangs ; $j++) {
      $attrvals[$j] = array();
	    for ($k = 0 ; $k < $ndspattrs ; $k++) {
        $str = "SELECT ifnull(attrval,'') as attrval FROM t_itemext where iditem = '".$iditems[$i]."' and idattr = '".$dspattrs[$k]."' and idlang = ".$idlangs[$j].";";
        $n = $db->querySelect($str);
        if ($n == 1) {
          $row = $db->goNext();;
          $attrvals[$j][$k] = $row['attrval'];
        }
        else {
          $attrvals[$j][$k] = '';
        }
	    }
      
      $nmgrp = '';
      for ($k = 0, $m = count($nmgrps[$j]) ; $k < $m ; $k++) {
        if ($idgrps[$j][$k] == $idgrps2[$i]) {
          $nmgrp = $nmgrps[$j][$k];
        }
      }
        
/*        $arrLists = array("image"=>$base_URL.$images[$i],
                        $attrnams[$j][$idxdspattrs["fullname"]]=>$attrvals[$j][$idxdspattrs["fullname"]],
                        $attrnams[$j][$idxdspattrs["institution"]]=>$attrvals[$j][$idxdspattrs["institution"]],
                        $attrnams[$j][$idxdspattrs["designation"]]=>$attrvals[$j][$idxdspattrs["designation"]]);
*/        
      $arrDetails = array("image"=>$base_URL.str_replace($imgDir, $imgDir2, $attrvals[$j][$idxdspattrs["imgdesc"]]),
                        "feature"=>$attrvals[$j][$idxdspattrs["feature"]],
                        "thumbnail"=>$base_URL.str_replace($imgDir, $imgDir2, $images[$i]),
                        $attrnams[$j][$idxdspattrs["nationera"]]=>$attrvals[$j][$idxdspattrs["nationera"]],
                        $attrnams[$j][$idxdspattrs["material"]]=>$attrvals[$j][$idxdspattrs["material"]],
                        $attrnams[$j][$idxdspattrs["size"]]=>$attrvals[$j][$idxdspattrs["size"]],
                        $attrnams[$j][$idxdspattrs["institution"]]=>$attrvals[$j][$idxdspattrs["institution"]],
                        $attrnams[$j][$idxdspattrs["designation"]]=>$attrvals[$j][$idxdspattrs["designation"]],
                        $attrnams[$j][$idxdspattrs["relicnumber"]]=>$attrvals[$j][$idxdspattrs["relicnumber"]]);
        
      $arrDesc[$j] = array("language"=>$ennmlangs[$j],
                          "name"=>$attrvals[$j][$idxdspattrs["fullname"]],
//                            "order"=>$attrvals[$j][$idxdspattrs["consonant"]],
                          "group"=>$nmgrp,
//                            "list"=>$arrLists,
                          "detail"=>$arrDetails);
      if ($j == 0) {
        $konmgrp = $nmgrp;
      }
	  }
      
    $arrItems[$i] = array("name"=>$nmitems[$i], "model"=>$base_URL.str_replace($modelDir, $modelDir2, $models[$i]), "group"=>$konmgrp, "desc"=>$arrDesc);
	}
  
  $outarr = array("language"=>$arrLang, "group"=>$arrGroup, "itemlist"=>$arrItems);
  $outstr = json_encode($outarr, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT);
  return $outstr;
}
?>

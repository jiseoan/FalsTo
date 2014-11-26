<?PHP
header("Content-Type:text/html; charset=UTF-8"); 
header("Cache-Control:no-cache");
header("Pragma:no-cache");
session_start();

$curpage = isset($_POST["curpage"]) ? intval($_POST["curpage"]) : 1;
?>
<?php include './common/db.php'; ?>
<?php

$ok = $db->open();

$dftLang = 1;	// 한국어
$idlangs = null;
$nmlangs = null;
$idgrp = 0;
$idbrs = null;
$names = array();
$nidbrs = 0;
$nidlangs = 0;
$attrnames = array();

if ($ok) {
	$str = "SELECT idgrp FROM t_grp where name = 'FOODMENU';";
	$idgrp = $db->queryCount($str, "idgrp");

	$idlangs = $db->getLangList();
	$nidlangs = count($idlangs);

	$str = "SELECT name FROM t_lang order by idlang;";
	$nmlangs = $db->getSingleList($str, "name");

	$str = "SELECT distinct i.iditem, attrval FROM t_item as i inner join t_grp as g on (g.idgrpcateg = 1 and i.idgrp = g.idgrp and g.idlang = 1) inner join t_itemext as a on (i.iditem = a.iditem and idattr = 'name' and a.idlang = 1) order by attrval;";
	$nidbrs = $db->querySelect($str);
	$idbrs = array();
	$names = array();

	for ($i = 0 ; $i < $nidbrs ; $i++) {
		$row = $db->goNext();
		$idbrs[$i] = $row['iditem'];
		$names[$i] = $row['attrval'];
	}
	$db->free();

	$str = "SELECT distinct idattr FROM t_itemattr order by idattr;";
	$idattrs = $db->getSingleList($str, "idattr");
	$nidattrs = count($idattrs);

	for ($i = 0 ; $i < $nidattrs ; $i++) {
		$str = "SELECT name FROM t_itemattr where idattr = '".$idattrs[$i]."' and idlang = ".$dftLang.";";
		$n = $db->querySelect($str);
		if ($n == 1) {
		  $row = $db->goNext();
		  $attrnames[$i] = $row['name'];
		}
		else {
		  $attrnames[$i] = $idattrs[$i];
	    }
		$db->free();
	}

	$db->close();
}
?>


<html>
<head>
<title>
FOOD MENU 관리
</title>
<link rel="stylesheet" type="text/css" href="./css/subpage.css" />
<link rel="stylesheet" type="text/css" href="./css/body.css" />
<script type="text/javascript" src="./js/update.js" ></script>
</head>
<body style="margin-left:0px; margin-top:0px; font-family: 돋움;">

<image src="./image/add.png" style="position:relative; left:0px; top:15px; width: 49px; height:32px; cursor:pointer;" alt="추가" onclick="addForm.action='subfoodmenuadd.php'; addForm.submit();"/>
<image src="./image/cancel.png" style="position:relative; left:0px; top:15px; width: 49px; height:32px; cursor:pointer;" alt="취소" onclick="addForm.action='subfoodmenuman.php'; addForm.submit();"/>

<form id="addForm" name="addForm" enctype="multipart/form-data" method="post" action="">
<input type="hidden" name="curpage" value="<? echo $curpage; ?>" />
<input type="hidden" name="idgrp" value="<? echo $idgrp; ?>" />

<table style="position:relative; left:0px; top:30px; width:945px;" class="tbl">
<tr style="height:42px;">
<th style="width:145px;" class="th1">속성</th>
<?php
for ($i = 0 ; $i < $nidlangs ; $i++) {
	echo '<th style="width:200px;" class="th2">'.$nmlangs[$i].'</th>';
}
?>
</tr>
<tr style="height:42px;">
<td style="width:145px; text-align:center;" class="td1">브랜드</td>
<td colspan="5" style="width:945px; text-align:left;" class="td2"><select name="idparentitem" onchange="selectionSync(this);" style="width:150px;">
<?php
	for ($j = 0 ; $j < $nidbrs ; $j++) {
    if ($j == 0) {
	    echo '<option value="'.$idbrs[$j].'" selected>'.$names[$j].'</option>';
    }
    else {
	    echo '<option value="'.$idbrs[$j].'">'.$names[$j].'</option>';
    }
  }
?>
</select></td>
</tr>
<?php
for ($i = 0 ; $i < $nidattrs ; $i++) {
?>
<tr>
<td style="width:145px; text-align:center;" class="td1"><? echo $attrnames[$i]; ?></td>
<?php
  for ($j = 0 ; $j < $nidlangs ; $j++) {
?>
<td style="width:160px; text-align:center;" class="td2">
<textarea name="cons[]" rows="10" cols="100" style="width: 100%; border-style:none; overflow-y:scroll;"></textarea>
<input type="hidden" name="attr[]" value="<? echo $idattrs[$i]; ?>" /><input type="hidden" name="idlang[]" value="<? echo $idlangs[$j]; ?>" /></td>
<?php
  }
?>
</tr>
<?php
}
?>
<tr style="height:42px;">
<td style="width:145px; text-align:center;" class="td1">이미지</td>
<td colspan="5" style="width:945px; text-align:center;" class="td2"><input type="file" name="file[]" style="width:750px; border-style:none;"/><input type="hidden" name="attrfile[]" value="pathimage" /></td>
</tr>
<tr style="height:42px;">
<td style="width:145px; text-align:center;" class="td1">가격</td>
<td colspan="5" style="width:945px; text-align:center;" class="td2"><input type="text" name="price"  style="border-style:solid; border-width: 1px; border-color: #d9d9d9; text-align:left; width:95%;" value=""/></td>
</tr>
<tr style="height:42px;">
<td style="width:145px; text-align:center;" class="td1">유사어(Tag)</td>
<td colspan="5" style="width:945px; text-align:center;" class="td2"><textarea name="tag" rows="2" cols="100" style="width: 100%; border-style:none; overflow-y:scroll;"></textarea></td>
</tr>
</table>
</form>
</div>

</body>
</html>

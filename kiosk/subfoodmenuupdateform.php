<?PHP
header("Content-Type:text/html; charset=UTF-8"); 
header("Cache-Control:no-cache");
header("Pragma:no-cache");
session_start();

$del = $_POST["del"];
$curpage = isset($_POST["curpage"]) ? intval($_POST["curpage"]) : 1;
?>
<?php include './common/db.php'; ?>
<?php

$ok = $db->open();

$dftLang = 1;	// 한국어
$iditem = $del[0];
$pathimage = '';
$idgrp = 0;
$idlangs = null;
$nmlangs = null;
$idbrs = null;
$names = array();
$nidbrs = 0;
$nidlangs = 0;
$attrnames = array();
$nidattrs = 0;
$attrvals = array();
$tag = '';
$price = '';
$idbrand = 0;

if ($ok) {
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

		$attrvals[$i] = array();
		for ($j = 0 ; $j < $nidlangs ; $j++) {
			$str = "SELECT ifnull(attrval,'') as attrval FROM t_itemext where iditem = '".$iditem."' and idattr = '".$idattrs[$i]."' and idlang = ".$idlangs[$j].";";
			$n = $db->querySelect($str);
			if ($n == 1) {
				$row = $db->goNext();;
				$attrvals[$i][$j] = $row['attrval'];
			}
			else {
				$attrvals[$i][$j] = '';
			}
			$db->free();
		}

		$str = "SELECT idparentitem, idgrp, ifnull(pathimage,'') as pathimage, ifnull(tag,'') as tag, ifnull(price,0) as price FROM t_item where iditem = ".$iditem.";";
		$m = $db->querySelect($str);
		if ($m == 1) {
			$row = $db->goNext();
			$idbrand = $row['idparentitem'];
			$idgrp = $row['idgrp'];
			$pathimage = $row['pathimage'];
			$tag = $row['tag'];
			$price = $row['price'];
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
<script type="text/javascript" src="./js/selectsync.js" ></script>
</head>
<body style="margin-left:0px; margin-top:0px; font-family: 돋움;">

<image src="./image/change.png" style="position:relative; left:0px; top:15px; width: 49px; height:32px; cursor:pointer;" alt="수정" onclick="addForm.submit();"/>
<image src="./image/cancel.png" style="position:relative; left:0px; top:15px; width: 49px; height:32px; cursor:pointer;" alt="취소" onclick="addForm.action='subfoodmenuman.php'; addForm.submit();"/>

<form id="addForm" name="addForm" enctype="multipart/form-data" method="post" action="subfoodmenuupdate.php">
<input type="hidden" name="curpage" value="<? echo $curpage; ?>" />
<input type="hidden" name="iditem" value="<? echo $iditem; ?>" />
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
    if ($idbrand == $idbrs[$j]) {
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
<textarea name="cons[]" rows="10" cols="100" style="width: 100%; border-style:none; overflow-y:scroll;"><? echo htmlspecialchars(stripslashes($attrvals[$i][$j])); ?></textarea>
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
<td colspan="5" style="width:945px; text-align:center;" class="td2"><?php
if (strlen($pathimage) > 0) {
	echo '<a href="'.$pathimage.'" target="new"><image src="'.$pathimage.'" style="position:relative; top:0px; height:100px;" alt="이미지"></a>';
}
?><br/><input type="file" name="file[]" style="width:750px; border-style:none;"/><input type="hidden" name="attrfile[]" value="pathimage" /></td>
</tr>
<tr style="height:42px;">
<td style="width:145px; text-align:center;" class="td1">가격</td>
<td colspan="5" style="width:945px; text-align:center;" class="td2"><input type="text" name="price"  style="border-style:solid; border-width: 1px; border-color: #d9d9d9; text-align:left; width:95%;" value="<? echo $price; ?>"/></td>
</tr>
<tr style="height:42px;">
<td style="width:145px; text-align:center;" class="td1">유사어(Tag)</td>
<td colspan="5" style="width:945px; text-align:center;" class="td2"><textarea name="tag" rows="2" cols="100" style="width: 100%; border-style:none; overflow-y:scroll;"><? echo htmlspecialchars(stripslashes($tag)); ?></textarea></td>
</tr>
</table>
</form>
</div>

</body>
</html>

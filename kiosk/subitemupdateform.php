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
$idgrps = null;
$names = array();
$nidgrps = 0;
$nidlangs = 0;
$attrnames = array();
$nidattrs = 0;
$attrvals = array();
$hall = '';
$floor = '';
$xpos = '';
$ypos = '';
$phone = '';
$tag = '';

if ($ok) {
	$idlangs = $db->getLangList();
	$nidlangs = count($idlangs);

	$str = "SELECT name FROM t_lang order by idlang;";
	$nmlangs = $db->getSingleList($str, "name");

	$str = "SELECT distinct idgrp FROM t_grp where idgrpcateg = 1 order by idgrp;";
	$idgrps = $db->getSingleList($str, "idgrp");
	$nidgrps = count($idgrps);

	for ($i = 0 ; $i < $nidgrps ; $i++) {
		$names[$i] = array();
		for ($j = 0 ; $j < $nidlangs ; $j++) {
			$names[$i][$j] = '';
		}

		$str = "SELECT idlang, name FROM t_grp where idgrp = ".$idgrps[$i]." order by seq;";
		$n = $db->querySelect($str);
		for ($j = 0 ; $j < $n ; $j++) {
			$row = $db->goNext();
			$k = $db->lookupIndex($idlangs, $nidlangs, $row['idlang']);
			$names[$i][$k] = $row['name'];
		}
		$db->free();
	}

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

		$str = "SELECT idgrp, ifnull(pathimage,'') as pathimage, ifnull(hall,'') as hall, ifnull(floor,'') as floor, xpos, ypos, ifnull(phone,'') as phone, ifnull(tag,'') as tag FROM t_item where iditem = ".$iditem.";";
		$m = $db->querySelect($str);
		if ($m == 1) {
			$row = $db->goNext();
			$idgrp = $row['idgrp'];
			$pathimage = $row['pathimage'];
			$hall = $row['hall'];
			$floor = $row['floor'];
			$xpos = $row['xpos'];
			$ypos = $row['ypos'];
			$phone = $row['phone'];
			$tag = $row['tag'];
		}
		
		$db->free();
	}

	$db->close();
}

$tel = explode(".", $phone);
if (count($tel) != 3) {
	$tel = array();
	$tel[0] = '02';
	$tel[1] = '0000';
	$tel[2] = '0000';
}
?>


<html>
<head>
<title>
브랜드관리
</title>
<link rel="stylesheet" type="text/css" href="./css/subpage.css" />
<link rel="stylesheet" type="text/css" href="./css/body.css" />
<script type="text/javascript" src="./js/update.js" ></script>
<script type="text/javascript" src="./js/selectsync.js" ></script>
</head>
<body style="margin-left:0px; margin-top:0px; font-family: 돋움;">

<image src="./image/change.png" style="position:relative; left:0px; top:15px; width: 49px; height:32px; cursor:pointer;" alt="수정" onclick="addForm.submit();"/>
<image src="./image/cancel.png" style="position:relative; left:0px; top:15px; width: 49px; height:32px; cursor:pointer;" alt="취소" onclick="addForm.action='subitemman.php'; addForm.submit();"/>

<form id="addForm" name="addForm" enctype="multipart/form-data" method="post" action="subitemupdate.php">
<input type="hidden" name="curpage" value="<? echo $curpage; ?>" />
<input type="hidden" name="iditem" value="<? echo $iditem; ?>" />
<table style="position:relative; left:0px; top:30px; width:945px;" class="tbl">
<tr style="height:42px;">
<th style="width:145px;" class="th1">속성</th>
<?php
for ($i = 0 ; $i < $nidlangs ; $i++) {
	echo '<th style="width:200px;" class="th2">'.$nmlangs[$i].'<input type="hidden" name="idlang[]" value="'.$idlangs[$i].'"/></th>';
}
?>
</tr>
<tr style="height:42px;">
<td style="width:145px; text-align:center;" class="td1">분류</td>
<?php
for ($i = 0 ; $i < $nidlangs ; $i++) {
	echo '<td style="width:200px; text-align:center;" class="td2"><select name="conssel[]" onchange="selectionSync(this);" style="width:150px;">';
	for ($j = 0 ; $j < $nidgrps ; $j++) {
		if ($idgrps[$j] == $idgrp) {
			echo '<option value="'.$idgrps[$j].'" selected>'.$names[$j][$i].'</option>';
		}
		else {
			echo '<option value="'.$idgrps[$j].'">'.$names[$j][$i].'</option>';
		}
	}
	echo '</select><input type="hidden" name="attrsel[]" value="idgrp" /><input type="hidden" name="idlangsel[]" value="'.$idlangs[$i].'" /></td>';
}
?>
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
<tr>
<td style="width:145px; text-align:center;" class="td1">이미지</td>
<td colspan="5" style="width:945px; text-align:center;" class="td2"><?php
if (strlen($pathimage) > 0) {
	echo '<a href="'.$pathimage.'"><image src="'.$pathimage.'" style="position:relative; top:5px; height:160px;" alt="이미지"></a>';
}
?><br/><input type="file" name="file[]" style="width:750px; border-style:none;"/><input type="hidden" name="attrfile[]" value="pathimage" /></td>
</tr>
<tr style="height:42px;">
<td style="width:145px; text-align:center;" class="td1">위치</td>
<td colspan="5" style="width:945px; text-align:left;" class="td2"><select name="hall[]" style="width:100px;">
    <option value="EAST" <? echo ($hall == 'EAST' ? 'selected' : ''); ?>>EAST</option>
	<option value="WEST" <? echo ($hall == 'WEST' ? 'selected' : ''); ?>>WEST</option>
	</select>
	<select name="floor[]" style="width:60px;">
    <option value="B1F" <? echo ($floor == 'B1F' ? 'selected' : ''); ?>>B1F</option>
    <option value="1F" <? echo ($floor == '1F' ? 'selected' : ''); ?>>1F</option>
    <option value="2F" <? echo ($floor == '2F' ? 'selected' : ''); ?>>2F</option>
    <option value="3F" <? echo ($floor == '3F' ? 'selected' : ''); ?>>3F</option>
    <option value="4F" <? echo ($floor == '4F' ? 'selected' : ''); ?>>4F</option>
    <option value="5F" <? echo ($floor == '5F' ? 'selected' : ''); ?>>5F</option>
	</select>
	, 좌표 X<input type="text" name="xpos[]"  maxlength="5" style="border-style:none; text-align:center; width:50px;" value="<? echo $xpos; ?>"/>&nbsp;Y<input type="text" name="ypos[]"  maxlength="5" style="border-style:none; text-align:center; width:50px;" value="<? echo $ypos; ?>"/>
	<input type="button" value="좌표선택" onclick="mapShow(0)"/></td>
</tr>
<tr style="height:42px;">
<td style="width:145px; text-align:center;" class="td1">전화번호</td>
<td colspan="5" style="width:945px; text-align:left;" class="td2"><input type="text" name="tel[]"  maxlength="4" style="border-style:none; text-align:center; width:60px;" value="<? echo $tel[0]; ?>"/>&nbsp;-&nbsp;<input type="text" name="tel[]"  maxlength="4" style="border-style:none; text-align:center; width:60px;" value="<? echo $tel[1]; ?>"/>&nbsp;-&nbsp;<input type="text" name="tel[]"  maxlength="4" style="border-style:none; text-align:center; width:60px;" value="<? echo $tel[2]; ?>"/></td>
</tr>
<tr style="height:42px;">
<td style="width:145px; text-align:center;" class="td1">유사어(Tag)</td>
<td colspan="5" style="width:945px; text-align:center;" class="td2"><textarea name="tag" rows="2" cols="100" style="width: 100%; border-style:none; overflow-y:scroll;"><? echo htmlspecialchars(stripslashes($tag)); ?></textarea></td>
</tr>
</table>
</form>
</div>

<!-- 좌표선택 화면 -->
<div id="mapview" style="position:absolute; left:0px; top:0px; width: 100%; height:100%; z-index:9999; background-color:#f1f1f1; visibility:hidden;">
<image src="./image/ok.png" style="position:relative; left:0px; top:15px; width: 49px; height:32px; cursor:pointer;" alt="확인" onclick="coordinateApply(); mapview.style.visibility = 'hidden';"/>
<image src="./image/cancel.png" style="position:relative; left:0px; top:15px; width: 49px; height:32px; cursor:pointer;" alt="취소" onclick="mapview.style.visibility = 'hidden';"/>

<image name="map" src="" alt="맵" style="position:absolute; left:0px; top:80px;" onclick = "coordinateSelect()" />
<image name="here" src="./image/here.png" alt="여기" style="position:absolute; left:0px; top:0px;"/>
</div>

</body>
</html>

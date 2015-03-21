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
$idgrps = null;
$names = array();
$nidgrps = 0;
$nidlangs = 0;
$attrnames = array();

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
	}

	$db->close();
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

<image src="./image/add.png" style="position:relative; left:0px; top:15px; width: 49px; height:32px; cursor:pointer;" alt="추가" onclick="addForm.action='subitemadd.php'; addForm.submit();"/>
<image src="./image/cancel.png" style="position:relative; left:0px; top:15px; width: 49px; height:32px; cursor:pointer;" alt="취소" onclick="addForm.action='subitemman.php'; addForm.submit();"/>

<form id="addForm" name="addForm" enctype="multipart/form-data" method="post" action="">
<input type="hidden" name="curpage" value="<? echo $curpage; ?>" />
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
    if ($j == 0) {
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
<td style="width:145px; text-align:center;" class="td1">위치</td>
<td colspan="5" style="width:945px; text-align:left;" class="td2"><select name="hall[]" style="width:100px;">
    <option value="EAST">EAST</option>
	<option value="WEST" selected>WEST</option>
	</select>
	<select name="floor[]" style="width:60px;">
    <option value="B1F">B1F</option>
    <option value="1F" selected>1F</option>
    <option value="2F">2F</option>
    <option value="3F">3F</option>
    <option value="4F">4F</option>
    <option value="5F">5F</option>
	</select>
	, 좌표 X<input type="text" name="xpos[]"  maxlength="5" style="border-style:none; text-align:center; width:50px;" value="0"/>&nbsp;Y:<input type="text" name="ypos[]"  maxlength="5" style="border-style:none; text-align:center; width:50px;" value="0"/>
	<input type="button" value="좌표선택" onclick="mapShow(0)"/></td>
</tr>
<tr style="height:42px;">
<td style="width:145px; text-align:center;" class="td1">전화번호</td>
<td colspan="5" style="width:945px; text-align:left;" class="td2"><input type="text" name="tel[]"  maxlength="4" style="border-style:none; text-align:center; width:60px;" value="02"/>&nbsp;-&nbsp;<input type="text" name="tel[]"  maxlength="4" style="border-style:none; text-align:center; width:60px;" value="0000"/>&nbsp;-&nbsp;<input type="text" name="tel[]"  maxlength="4" style="border-style:none; text-align:center; width:60px;" value="0000"/></td>
</tr>
<tr style="height:42px;">
<td style="width:145px; text-align:center;" class="td1">유사어(Tag)</td>
<td colspan="5" style="width:945px; text-align:center;" class="td2"><textarea name="tag" rows="2" cols="100" style="width: 100%; border-style:none; overflow-y:scroll;"></textarea></td>
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

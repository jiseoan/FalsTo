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
$maximage = 6;

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

date_default_timezone_set('Asia/Seoul');
$yearbgn = date("Y");

$applydate = time();
$yearfrom = date("Y", $applydate);
$monthfrom = date("m", $applydate);
$dayfrom = date("d", $applydate);
$hourfrom = date("H", $applydate);
$minutefrom = date("i", $applydate);

$applydate = strtotime("+7 days");
$yearto = date("Y", $applydate);
$monthto = date("m", $applydate);
$dayto = date("d", $applydate);
$hourto = date("H", $applydate);
$minuteto = date("i", $applydate);
?>


<html>
<head>
<title>
쇼핑정보관리
</title>
<link rel="stylesheet" type="text/css" href="./css/subpage.css" />
<link rel="stylesheet" type="text/css" href="./css/body.css" />
<script type="text/javascript" src="./js/update.js" ></script>
<script type="text/javascript" src="./js/selectsync.js" ></script>
</head>
<body style="margin-left:0px; margin-top:0px; font-family: 돋움;">

<image src="./image/add.png" style="position:relative; left:0px; top:15px; width: 49px; height:32px; cursor:pointer;" alt="추가" onclick="addForm.action='subshoppinginfoadd.php'; addForm.submit();"/>
<image src="./image/cancel.png" style="position:relative; left:0px; top:15px; width: 49px; height:32px; cursor:pointer;" alt="취소" onclick="addForm.action='subshoppinginfoman.php'; addForm.submit();"/>

<form id="addForm" name="addForm" enctype="multipart/form-data" method="post" action="">
<table style="position:relative; left:0px; top:30px; width:945px;" class="tbl">
<input type="hidden" name="curpage" value="<? echo $curpage; ?>" />
<tr style="height:42px;">
<th style="width:145px;" class="th1">속성</th>
<?php
for ($i = 0 ; $i < $nidlangs ; $i++) {
	echo '<th style="width:200px;" class="th2">'.$nmlangs[$i].'<input type="hidden" name="idlang[]" value="'.$idlangs[$i].'"/></th>';
}
?>
</tr>
<tr style="height:42px;">
<td style="width:145px; text-align:center;" class="td1">템플릿종류</td>
<td colspan="4" style="width:945px; text-align:left; padding-left:3px;" class="td2"><select style="width:200px;" name="tmpltype" onchange="templateTypeSelect(this);">
<option value="0" selected>TYPE0 (이미지1개)</option>
<option value="1">TYPE1 (이미지1개)</option>
<option value="2">TYPE2 (이미지6개)</option>
</select>&nbsp;&nbsp;&nbsp;<select style="width:200px;" name="site">
<option value="1" selected>명품관</option>
<option value="2">GOURMET494</option>
</select></td>
</tr>
<tr style="height:42px;">
<td style="width:145px; text-align:center;" class="td1">게시 시작</td>
<td colspan="4" style="width:945px; text-align:left;" class="td2"><select name="yearfrom">
<?
for ($k = 0, $year2 = $yearbgn ; $k < 10 ; $k++, $year2++) {
	echo '<option value="'.$year2.'" '.($year2 == $yearfrom ? "selected" : "").'>'.$year2.'</option>';
}
?>
</select>년&nbsp;&nbsp;&nbsp;<select name="monthfrom">
<?php
	for ($j = 1 ; $j <= 12 ; $j++) {
		echo '<option value="'.$j.'"'.($j == $monthfrom ? "selected" : "").'>'.$j.'</option>';
	}
?>
</select>월&nbsp;&nbsp;&nbsp;<select name="dayfrom">
<?php
	for ($j = 1 ; $j <= 31 ; $j++) {
		echo '<option value="'.$j.'"'.($j == $dayfrom ? "selected" : "").'>'.$j.'</option>';
	}
?>
</select>일&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<select name="hourfrom">
<?php
	for ($j = 0 ; $j <= 24 ; $j++) {
		echo '<option value="'.$j.'"'.($j == $hourfrom ? "selected" : "").'>'.$j.'</option>';
	}
?>
</select>시&nbsp;&nbsp;&nbsp;<select name="minutefrom">
<?php
	for ($j = 0 ; $j <= 59 ; $j++) {
		echo '<option value="'.$j.'"'.($j == $minutefrom ? "selected" : "").'>'.$j.'</option>';
	}
?>
</select>분</td>
</tr>
<tr style="height:42px;">
<td style="width:145px; text-align:center;" class="td1">게시 종료</td>
<td colspan="4" style="width:945px; text-align:left;" class="td2"><select name="yearto">
<?
for ($k = 0, $year2 = $yearbgn ; $k < 10 ; $k++, $year2++) {
	echo '<option value="'.$year2.'" '.($year2 == $yearto ? "selected" : "").'>'.$year2.'</option>';
}
?>
</select>년&nbsp;&nbsp;&nbsp;<select name="monthto">
<?php
	for ($j = 1 ; $j <= 12 ; $j++) {
		echo '<option value="'.$j.'"'.($j == $monthto ? "selected" : "").'>'.$j.'</option>';
	}
?>
</select>월&nbsp;&nbsp;&nbsp;<select name="dayto">
<?php
	for ($j = 1 ; $j <= 31 ; $j++) {
		echo '<option value="'.$j.'"'.($j == $dayto ? "selected" : "").'>'.$j.'</option>';
	}
?>
</select>일&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<select name="hourto">
<?php
	for ($j = 0 ; $j <= 24 ; $j++) {
		echo '<option value="'.$j.'"'.($j == $hourto ? "selected" : "").'>'.$j.'</option>';
	}
?>
</select>시&nbsp;&nbsp;&nbsp;<select name="minuteto">
<?php
	for ($j = 0 ; $j <= 59 ; $j++) {
		echo '<option value="'.$j.'"'.($j == $minuteto ? "selected" : "").'>'.$j.'</option>';
	}
?>
</select>분</td>
</tr>
<tr style="height:42px;">
<td style="width:145px; text-align:center;" class="td1">제목</td>
<?php
for ($i = 0 ; $i < $nidlangs ; $i++) {
	echo '<td style="width:200px; text-align:center;" class="td2"><textarea name="title[]" rows="2" cols="100" style="width: 100%; border-style:none; overflow-y:scroll;"></textarea></td>';
}
?>
</tr>
<tr style="height:42px;">
<td style="width:145px; text-align:center;" class="td1">설명</td>
<?php
for ($i = 0 ; $i < $nidlangs ; $i++) {
	echo '<td style="width:200px; text-align:center;" class="td2"><textarea name="descinfo[]" rows="2" cols="100" style="width: 100%; border-style:none; overflow-y:scroll;"></textarea></td>';
}
?>
</tr>
<tr style="height:42px;">
<td style="width:145px; text-align:center;" class="td1">이미지설명</td>
<?php
for ($i = 0 ; $i < $nidlangs ; $i++) {
	echo '<td style="width:200px; text-align:center;" class="td2"><textarea name="descimg[]" rows="2" cols="100" style="width: 100%; border-style:none; overflow-y:scroll;"></textarea></td>';
}
?>
</tr>
<tr style="height:42px;">
<td style="width:145px; text-align:center;" class="td1">증정 대상</td>
<?php
for ($i = 0 ; $i < $nidlangs ; $i++) {
	echo '<td style="width:200px; text-align:center;" class="td2"><textarea name="target[]" rows="2" cols="100" style="width: 100%; border-style:none; overflow-y:scroll;"></textarea></td>';
}
?>
</tr>
<tr style="height:42px;">
<td style="width:145px; text-align:center;" class="td1">증정 기간</td>
<?php
for ($i = 0 ; $i < $nidlangs ; $i++) {
	echo '<td style="width:200px; text-align:center;" class="td2"><textarea name="period[]" rows="2" cols="100" style="width: 100%; border-style:none; overflow-y:scroll;"></textarea></td>';
}
?>
</tr>
<tr style="height:42px;">
<td style="width:145px; text-align:center;" class="td1">증정 장소</td>
<?php
for ($i = 0 ; $i < $nidlangs ; $i++) {
	echo '<td style="width:200px; text-align:center;" class="td2"><textarea name="location[]" rows="2" cols="100" style="width: 100%; border-style:none; overflow-y:scroll;"></textarea></td>';
}
?>
</tr>
<tr style="height:42px;">
<td style="width:145px; text-align:center;" class="td1">기타 설명</td>
<?php
for ($i = 0 ; $i < $nidlangs ; $i++) {
	echo '<td style="width:200px; text-align:center;" class="td2"><textarea name="descetc[]" rows="2" cols="100" style="width: 100%; border-style:none; overflow-y:scroll;"></textarea></td>';
}
?>
</tr>
<tr style="height:42px;">
<td style="width:145px; text-align:center;" class="td1">Ticker</td>
<?php
for ($i = 0 ; $i < $nidlangs ; $i++) {
	echo '<td style="width:200px; text-align:center;" class="td2"><textarea name="ticker[]" rows="2" cols="100" style="width: 100%; border-style:none; overflow-y:scroll;"></textarea></td>';
}
?>
</tr>
<tr style="height:32px;">
<td style="width:145px; text-align:center;" class="td1">Thumbnail</td>
<td colspan="4" style="width:800px; text-align:center;" class="td2"><input type="file" name="thumbnail" style="width:95%; border-style:none;"/></td>
</tr>
<?php
for ($j = 1 ; $j <= $maximage ; $j++) {
	echo '<tr style="height:32px;">';
	echo '<td style="width:145px; text-align:center;" class="td1">이미지'.$j.'</td>';
	for ($i = 0 ; $i < $nidlangs ; $i++) {
		echo '<td style="width:200px; text-align:center;" class="td2"><input type="file" name="file[]" style="width:95%; border-style:none;"/></td>';
	}
	echo '</tr>';
}
?>
</table>
</form>
</div>

<script>
templateTypeSelect(addForm.tmpltype);
</script>
</body>
</html>

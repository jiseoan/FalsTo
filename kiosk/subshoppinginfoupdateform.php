<?PHP
header("Content-Type:text/html; charset=UTF-8"); 
header("Cache-Control:no-cache");
header("Pragma:no-cache");
session_start();

$del = $_POST["del"];
?>
<?php include './common/db.php'; ?>
<?php

$ok = $db->open();

$curpage = isset($_POST["curpage"]) ? intval($_POST["curpage"]) : 1;
$dftLang = 1;	// 한국어
$idlangs = null;
$nmlangs = null;
$idshopinfo = $del[0];
$tmpltype = 1;
$site = 1;
$yearfrom = 0;
$monthfrom = 0;
$dayfrom = 0;
$hourfrom = 0;
$minutefrom = 0;
$yearto = 0;
$monthto = 0;
$dayto = 0;
$hourto = 0;
$minuteto = 0;
$title = array();
$descinfo = array();
$descimg = array();
$target = array();
$period = array();
$location = array();
$descetc = array();
$ticker = array();
$imgpath = array();
$postbegin = "";
$postend = "";
$thumbnail = "";
$maximage = 6;
$countimages = array(1, 1, 6);

if ($ok) {
	$idlangs = $db->getLangList();
	$nidlangs = count($idlangs);

	$str = "SELECT name FROM t_lang order by idlang;";
	$nmlangs = $db->getSingleList($str, "name");

	$str = "SELECT tmpltype, site, postbegin, postend, thumbnail FROM t_shoppinginfo where idshopinfo = ".$idshopinfo.";";
	$n = $db->querySelect($str);
	if ($n == 1) {
		$row = $db->goNext();
		$tmpltype = $row['tmpltype'];
		$site = $row['site'];
		$postbegin = $row['postbegin'];
		$postend = $row['postend'];
		$thumbnail = $row['thumbnail'];
		$maximage = $countimages[$tmpltype];
	}
	else {
		$ok = false;
	}
	
	$db->free();

	if ($ok) {
		$str = "SELECT idlang, title, descinfo, descimg, target, period, location, descetc, ticker FROM t_shoppinginfoext where idshopinfo = ".$idshopinfo." order by idlang;";
		$n = $db->querySelect($str);
		for ($i = 0 ; $i < $n ; $i++) {
			$row = $db->goNext();
			$idx = (int)$row['idlang'] - 1;
			$title[$idx] = $row['title'];
			$descinfo[$idx] = $row['descinfo'];
			$descimg[$idx] = $row['descimg'];
			$target[$idx] = $row['target'];
			$period[$idx] = $row['period'];
			$location[$idx] = $row['location'];
			$descetc[$idx] = $row['descetc'];
			$ticker[$idx] = $row['ticker'];
		}
		
		$db->free();
	}

	if ($ok) {
		$str = "SELECT idlang, seq, path FROM t_shoppinginfoimage where idshopinfo = ".$idshopinfo." order by seq, idlang;";
		$n = $db->querySelect($str);
		for ($i = 0 ; $i < $n ; $i++) {
			$row = $db->goNext();
			$idx = ((int)$row['seq'] - 1) * $nidlangs + ((int)$row['idlang'] - 1);
			$imgpath[$idx] = $row['path'];
		}
		
		$db->free();
	}

	$db->close();
}

date_default_timezone_set('Asia/Seoul');
$yearbgn = date("Y");


$applydate = strtotime($postbegin);
$yearfrom = date("Y", $applydate);
$monthfrom = date("m", $applydate);
$dayfrom = date("d", $applydate);
$hourfrom = date("H", $applydate);
$minutefrom = date("i", $applydate);

$applydate = strtotime($postend);
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

<image src="./image/change.png" style="position:relative; left:0px; top:15px; width: 49px; height:32px; cursor:pointer;" alt="수정" onclick="addForm.submit();"/>
<image src="./image/cancel.png" style="position:relative; left:0px; top:15px; width: 49px; height:32px; cursor:pointer;" alt="취소" onclick="addForm.action='subshoppinginfoman.php'; addForm.submit();"/>

<form id="addForm" name="addForm" enctype="multipart/form-data" method="post" action="subshoppinginfoupdate.php">
<input type="hidden" name="curpage" value="<? echo $curpage; ?>" />
<input type="hidden" name="idshopinfo" value="<? echo $idshopinfo; ?>" />
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
<td style="width:145px; text-align:center;" class="td1">템플릿종류</td>
<td colspan="4" style="width:945px; text-align:left; padding-left:3px;" class="td2">TYPE<? echo $tmpltype; ?><input type="hidden" name="tmpltype" value="<? echo $tmpltype; ?>"/>&nbsp;&nbsp;&nbsp;<? echo ($site == 1) ? "명품관" : "GOURMET494" ?></td>
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
	echo '<td style="width:200px; text-align:center;" class="td2"><textarea name="title[]" rows="2" cols="100" style="width: 100%; border-style:none; overflow-y:scroll;">'.htmlspecialchars(stripslashes($title[$i])).'</textarea></td>';
}
?>
</tr>
<tr style="height:42px;">
<td style="width:145px; text-align:center;" class="td1">설명</td>
<?php
for ($i = 0 ; $i < $nidlangs ; $i++) {
	echo '<td style="width:200px; text-align:center;" class="td2"><textarea name="descinfo[]" rows="2" cols="100" style="width: 100%; border-style:none; overflow-y:scroll;">'.htmlspecialchars(stripslashes($descinfo[$i])).'</textarea></td>';
}
?>
</tr>
<?
if ($tmpltype > 1) {
?>
<tr style="height:42px;">
<td style="width:145px; text-align:center;" class="td1">이미지설명</td>
<?php
for ($i = 0 ; $i < $nidlangs ; $i++) {
	echo '<td style="width:200px; text-align:center;" class="td2"><textarea name="descimg[]" rows="2" cols="100" style="width: 100%; border-style:none; overflow-y:scroll;">'.htmlspecialchars(stripslashes($descimg[$i])).'</textarea></td>';
}
?>
</tr>
<?
}
else if ($tmpltype > 0) {
?>
<tr style="height:42px;">
<td style="width:145px; text-align:center;" class="td1">증정 대상</td>
<?php
for ($i = 0 ; $i < $nidlangs ; $i++) {
	echo '<td style="width:200px; text-align:center;" class="td2"><textarea name="target[]" rows="2" cols="100" style="width: 100%; border-style:none; overflow-y:scroll;">'.htmlspecialchars(stripslashes($target[$i])).'</textarea></td>';
}
?>
</tr>
<?
}
?>
<tr style="height:42px;">
<td style="width:145px; text-align:center;" class="td1">증정 기간</td>
<?php
for ($i = 0 ; $i < $nidlangs ; $i++) {
	echo '<td style="width:200px; text-align:center;" class="td2"><textarea name="period[]" rows="2" cols="100" style="width: 100%; border-style:none; overflow-y:scroll;">'.htmlspecialchars(stripslashes($period[$i])).'</textarea></td>';
}
?>
</tr>
<?
if ($tmpltype > 0) {
?>
<tr style="height:42px;">
<td style="width:145px; text-align:center;" class="td1">증정 장소</td>
<?php
for ($i = 0 ; $i < $nidlangs ; $i++) {
	echo '<td style="width:200px; text-align:center;" class="td2"><textarea name="location[]" rows="2" cols="100" style="width: 100%; border-style:none; overflow-y:scroll;">'.htmlspecialchars(stripslashes($location[$i])).'</textarea></td>';
}
?>
</tr>
<tr style="height:42px;">
<td style="width:145px; text-align:center;" class="td1">기타 설명</td>
<?php
for ($i = 0 ; $i < $nidlangs ; $i++) {
	echo '<td style="width:200px; text-align:center;" class="td2"><textarea name="descetc[]" rows="2" cols="100" style="width: 100%; border-style:none; overflow-y:scroll;">'.htmlspecialchars(stripslashes($descetc[$i])).'</textarea></td>';
}
?>
</tr>
<?
}
?>
<tr style="height:42px;">
<td style="width:145px; text-align:center;" class="td1">Ticker</td>
<?php
for ($i = 0 ; $i < $nidlangs ; $i++) {
	echo '<td style="width:200px; text-align:center;" class="td2"><textarea name="ticker[]" rows="2" cols="100" style="width: 100%; border-style:none; overflow-y:scroll;">'.htmlspecialchars(stripslashes($ticker[$i])).'</textarea></td>';
}
?>
</tr>
<tr style="height:32px;">
<td style="width:145px; text-align:center;" class="td1">Thumbnail</td>
<td colspan="4" style="width:800px; text-align:center;" class="td2"><?
if (strlen($thumbnail) > 0) {
	echo '<a href="'.$thumbnail.'"><image src="'.$thumbnail.'" style="position:relative; top:5px; width:40px; height:25px;" alt="이미지"></a>';
}
?><input type="file" name="thumbnail" style="width:95%; border-style:none;"/></td>
</tr>
<?php
for ($j = 1, $k = 0 ; $j <= $maximage ; $j++) {
	echo '<tr style="height:30px;">';
	echo '<td style="width:145px; text-align:center;" class="td1">이미지'.$j.'</td>';
	for ($i = 0 ; $i < $nidlangs ; $i++, $k++) {
		echo '<td style="width:200px; text-align:center;" class="td2">';
		if (strlen($imgpath[$k]) > 0) {
			echo '<a href="'.$imgpath[$k].'"><image src="'.$imgpath[$k].'" style="position:relative; top:5px; width:40px; height:25px;" alt="이미지"></a>';
		}
		echo '&nbsp;<input type="file" name="file[]" style="width:150px; border-style:none;"/></td>';
	}
	echo '</tr>';
}
?>
</table>
</form>
</div>

</body>
</html>

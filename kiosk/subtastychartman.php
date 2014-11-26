<?PHP
header("Content-Type:text/html; charset=UTF-8"); 
header("Cache-Control:no-cache");
header("Pragma:no-cache");
session_start();
?>
<?php include './common/db.php'; ?>

<?php

date_default_timezone_set('Asia/Seoul');
$cur = time();
$year = intval(date("Y"));
$month = intval(date("m"));

$idlangs = null;
$nmlangs = null;
$nidlangs = 0;
$idgrps = null;
$paths = array();

$ok = $db->open();
if ($ok) {
	/* 효율을 위하여, 개발 시 한번만 실행하고 주석처리 함.
	$yearbegin = 2012;
	$monthbegin = 10;
	
	for ($i = $yearbegin ; $i <= $year ; $i++) {
		for ($j = $monthbegin ; ($i < $year && $j <= 12) || ($i == $year && $j < $month) ; $j++) {
			for ($k = 1 ; $k < 5 ; $k++) {
				$str = "INSERT INTO t_tastychart (year, month, idlang, pathimage) VALUES (".$i.", ".$j.", ".$k.", '');";
				$db->query($str);
			}
		}
	}
	*/

	$idlangs = $db->getLangList();
	$nidlangs = count($idlangs);

	$str = "SELECT name FROM t_lang order by idlang;";
	$nmlangs = $db->getSingleList($str, "name");

	// 현재 년/월의 초기값 삽입
	for ($i = 0 ; $i < $nidlangs ; $i++) {
		$str = "INSERT INTO t_tastychart (year, month, idlang, pathimage) VALUES (".$year.", ".$month.", ".$idlangs[$i].", '');";
		$db->query($str);
	}

	$str = "SELECT year, month, idlang, pathimage FROM t_tastychart order by year desc, month desc, idlang;";
	$n = $db->querySelect($str);
	for ($j = 0 ; $j < $n ; $j++) {
		$row = $db->goNext();
		$paths[$j] = array("year"=>$row['year'], "month"=>$row['month'], "idlang"=>$row['idlang'], "pathimage"=>$row['pathimage']);
	}
	$db->free();
	
	$db->close();
}
?>


<html>
<head>
<title>
TASTY CHART 관리
</title>
<link rel="stylesheet" type="text/css" href="./css/subpage.css" />
<link rel="stylesheet" type="text/css" href="./css/body.css" />
<script type="text/javascript" src="./js/update.js" ></script>
</head>
<body style="margin-left:0px; margin-top:0px; font-family: 돋움;">

<image src="./image/change.png" style="position:relative; left:0px; top:15px; width: 49px; height:32px; cursor:pointer;" alt="수정" onclick="regForm.submit();"/>

<form id="regForm" name="regForm" method="post" enctype="multipart/form-data" action="subtastychartupdate.php">
<table style="position:relative; left:0px; top:30px; width:920px;" class="tbl">
<tr style="height:42px;">
<th style="width:60px;" class="th1">년</th>
<th style="width:60px;" class="th2">월</th>
<th style="width:100px;" class="th2">언어</th>
<th style="width:700px;" class="th2">차트 이미지</th>
</tr>
<?php
	for ($i = 0, $n = count($paths) ; $i < $n ; $i++) {
		$idlang = $paths[$i]['idlang'];
?>
<tr style="height:32px;">
<?
		if ($idlang == 1) {
?>
			<td rowspan="<? echo $nidlangs ?>" style="width:75px; text-align:center;" class="td1"><? echo $paths[$i]['year']; ?></td>
			<td rowspan="<? echo $nidlangs ?>" style="width:70px; text-align:center;" class="td2"><? echo $paths[$i]['month']; ?></td>
<?
		}
?>
<td style="width:100px; text-align:center;" class="td2"><? echo $nmlangs[$idlang - 1]; ?></td>
<td style="width:700px; text-align:center;" class="td2"><?
		if (strlen($paths[$i]['pathimage']) > 0) {
?>
			<a href="<? echo $paths[$i]['pathimage']; ?>" target="new"><img style="position:relative; top:3px; height:20px;" src="<? echo $paths[$i]['pathimage']; ?>" alt=""/></a>
<?
		}
?>
<input type="file" name="file[]" style="width:85%; border-style:none;"/>
<input type="hidden" name="year[]" value="<? echo $paths[$i]['year']; ?>" />
<input type="hidden" name="month[]" value="<? echo $paths[$i]['month']; ?>" />
<input type="hidden" name="idlang[]" value="<? echo $paths[$i]['idlang']; ?>" />
<input type="hidden" name="orgpath[]" value="<? echo $paths[$i]['pathimage']; ?>" />
</td>
</tr>
<?php
	}
?>
</table>
</form>


</body>
</html>

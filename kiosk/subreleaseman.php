<?PHP
header("Content-Type:text/html; charset=UTF-8"); 
header("Cache-Control:no-cache");
header("Pragma:no-cache");
session_start();

$arrCtx = array("cntsrtattr"=>"appdate",
				"cntsrtodr"=>"desc");

foreach ($arrCtx as $k=>$v) {
	if (array_key_exists($k, $_GET)) {
		$arrCtx[$k] = $_GET[$k];
	}
	else if (array_key_exists($k, $_POST)) {
		$arrCtx[$k] = $_POST[$k];
	}
	else if (array_key_exists($k, $_SESSION)) {
		$arrCtx[$k] = $_SESSION[$k];
		continue;
	}
	$_SESSION[$k] = $arrCtx[$k];
}

$curpage = isset($_POST["curpage"]) ? intval($_POST["curpage"]) : 1;
?>
<?php include './common/db.php'; ?>
<?php

$ok = $db->open();

$maxitem = 5;
$relimgs = array();
$nrelimgs = 0;
$offset = ($curpage - 1) * $maxitem;
$nallitems = 0;
$curimage = 0;
$srtattr = array("name"=>"name", "blddate"=>"regdate", "appdate"=>"applydate", "bldimg"=>"pathimage", "state"=>"state");

if ($curpage <= 0) {
	$curpage = 1;
}

if ($ok) {
	$str = "SELECT count(*) as cnt FROM t_release where imgtype = 1;";
	$nallitems = $db->queryCount($str, "cnt");

	$maxpgno = ceil($nallitems / $maxitem);
	if ($maxpgno <= 0) {
		$maxpgno = 1;
	}
	if ($curpage > $maxpgno) {
		$curpage = $maxpgno;
		$offset = ($curpage - 1) * $maxitem;
	}

	if ($nallitems > 0) {
		$str = "SELECT idrelease, name, regdate, applydate, pathimage, if(applydate > now(), '예약', '만료') as state FROM t_release where imgtype = 1 order by ".$srtattr[$arrCtx["cntsrtattr"]]." ".$arrCtx["cntsrtodr"].", idrelease limit ".$offset.",".$maxitem.";";
		$nrelimgs = $db->querySelect($str);

		for ($i = 0 ; $i < $nrelimgs ; $i++) {
			$row = $db->goNext();;
			$relimgs[$i] = array();
			$relimgs[$i][0] = $row['idrelease'];
			$relimgs[$i][1] = $row['name'];
			$relimgs[$i][2] = $row['regdate'];
			$relimgs[$i][3] = $row['applydate'];
			$relimgs[$i][4] = $row['pathimage'];
			$relimgs[$i][5] = $row['state'];
		}
		$db->free();

		$str = "SELECT idrelease FROM t_release where imgtype = 1 and applydate <= now() order by applydate desc, idrelease limit 0, 1;";
		$curimage = $db->queryCount($str, "idrelease");
	}

	$db->close();
}

?>


<html>
<head>
<title>
업데이트관리
</title>
<link rel="stylesheet" type="text/css" href="./css/subpage.css" />
<script type="text/javascript" src="./js/update.js" ></script>
</head>
<body style="margin-left:0px; margin-top:0px; font-family: 돋움;">

<image src="./image/add.png" style="position:relative; left:0px; top:15px; width: 49px; height:32px; cursor:pointer;" alt="추가" onclick="loadingview.style.visibility = 'visible'; document.location.replace('subreleaseadd.php');"/>
<image src="./image/remove.png" style="position:relative; left:0px; top:15px; width: 49px; height:32px; cursor:pointer;" alt="삭제" onclick="if (testCheckbox('del[]')) { regForm.action='subreleaseremove.php'; regForm.submit(); }"/>
<image src="./image/change.png" style="position:relative; left:0px; top:15px; width: 49px; height:32px; cursor:pointer;" alt="수정" onclick="regForm.action='subreleaseupdate.php'; regForm.submit();"/>
<image src="./image/execute.png" style="position:relative; left:0px; top:15px; width: 49px; height:32px; cursor:pointer;" alt="실행" onclick="if (testCheckbox('del[]')) { regForm.action='subreleaseexecute.php'; regForm.submit(); }"/>

<form id="regForm" name="regForm" method="post" action="">
<input type="hidden" name="curpage" value="<? echo $curpage; ?>" />
<input type="hidden" name="imgtype" value="0" />
<input type="hidden" name="returnpath" value="subreleaseman.php" />
<table style="position:relative; left:0px; top:30px; width:945px;" class="tbl">
<tr style="height:42px;">
<th style="width:45px;" class="th1"><input type="checkbox" class="chkbox" onclick="toggleCheckbox(this.checked, 'del[]');"/></th>
<?
if ($arrCtx['cntsrtattr'] == "name") {
	if ($arrCtx['cntsrtodr'] == "asc") {
		$odr = "desc";
		$imghtml = '<image src="./image/sort.png" style="cursor:pointer;"/>';
	}
	else {
		$odr = "asc";
		$imghtml = '<image src="./image/sortr.png" style="cursor:pointer;"/>';
	}
}
else {
	$odr = "asc";
	$imghtml = "";
}
echo '<th style="width:150px; cursor:pointer;" class="th2" onclick="document.location = \'subreleaseman.php?cntsrtattr=name&cntsrtodr='.$odr.'\';">업데이트명'.$imghtml.'</th>';

if ($arrCtx['cntsrtattr'] == "blddate") {
	if ($arrCtx['cntsrtodr'] == "asc") {
		$odr = "desc";
		$imghtml = '<image src="./image/sort.png" style="cursor:pointer;"/>';
	}
	else {
		$odr = "asc";
		$imghtml = '<image src="./image/sortr.png" style="cursor:pointer;"/>';
	}
}
else {
	$odr = "asc";
	$imghtml = "";
}
echo '<th style="width:150px; cursor:pointer;" class="th2" onclick="document.location = \'subreleaseman.php?cntsrtattr=blddate&cntsrtodr='.$odr.'\';">생성일'.$imghtml.'</th>';

if ($arrCtx['cntsrtattr'] == "appdate") {
	if ($arrCtx['cntsrtodr'] == "asc") {
		$odr = "desc";
		$imghtml = '&nbsp;<image src="./image/sort.png" style="cursor:pointer;"/>';
	}
	else {
		$odr = "asc";
		$imghtml = '&nbsp;<image src="./image/sortr.png" style="cursor:pointer;"/>';
	}
}
else {
	$odr = "asc";
	$imghtml = "";
}
echo '<th style="width:400px; cursor:pointer;" class="th2" onclick="document.location = \'subreleaseman.php?cntsrtattr=appdate&cntsrtodr='.$odr.'\';">적용(예정)일'.$imghtml.'</th>';

if ($arrCtx['cntsrtattr'] == "bldimg") {
	if ($arrCtx['cntsrtodr'] == "asc") {
		$odr = "desc";
		$imghtml = '<image src="./image/sort.png" style="cursor:pointer;"/>';
	}
	else {
		$odr = "asc";
		$imghtml = '<image src="./image/sortr.png" style="cursor:pointer;"/>';
	}
}
else {
	$odr = "asc";
	$imghtml = "";
}
echo '<th style="width:100px; cursor:pointer;" class="th2" onclick="document.location = \'subreleaseman.php?cntsrtattr=bldimg&cntsrtodr='.$odr.'\';">이미지'.$imghtml.'</th>';

if ($arrCtx['cntsrtattr'] == "state") {
	if ($arrCtx['cntsrtodr'] == "asc") {
		$odr = "desc";
		$imghtml = '<image src="./image/sort.png" style="cursor:pointer;"/>';
	}
	else {
		$odr = "asc";
		$imghtml = '<image src="./image/sortr.png" style="cursor:pointer;"/>';
	}
}
else {
	$odr = "asc";
	$imghtml = "";
}
echo '<th style="width:100px; cursor:pointer;" class="th2" onclick="document.location = \'subreleaseman.php?cntsrtattr=state&cntsrtodr='.$odr.'\';">상태'.$imghtml.'</th>';
?>
</tr>
<?php
date_default_timezone_set('Asia/Seoul');
$yearbgn = date("Y");

for ($i = 0 ; $i < $nrelimgs ; $i++) {
	$applydate = strtotime($relimgs[$i][3]);
	$yearto = date("Y", $applydate);
	$monthto = date("m", $applydate);
	$dayto = date("d", $applydate);
	$hourto = date("H", $applydate);
	$minuteto = date("i", $applydate);
?>
<tr style="height:42px;">
<td style="width:45px; text-align:center;" class="td1"><input type="checkbox" name="del[]" class="chkbox" value="<? echo $relimgs[$i][0]; ?>"/><input type="hidden" name="idrelimg[]" value="<? echo $relimgs[$i][0]; ?>"/></td>
<td style="width:150px; text-align:center;" class="td2"><input type="text" name="riname[]"  maxlength="60" style="width: 100%; border-style:none; text-align:center;" value="<? echo $relimgs[$i][1]; ?>"/></td>
<td style="width:150px; text-align:center;" class="td2"><? echo $relimgs[$i][2]; ?></td>
<td style="width:400px; text-align:center;" class="td2"><select name="yearto[]">
<?
for ($k = 0, $year2 = $yearbgn ; $k < 10 ; $k++, $year2++) {
	echo '<option value="'.$year2.'" '.($year2 == $yearto ? "selected" : "").'>'.$year2.'</option>';
}
?>
</select>년&nbsp;&nbsp;&nbsp;<select name="monthto[]">
<?php
	for ($j = 1 ; $j <= 12 ; $j++) {
		echo '<option value="'.$j.'"'.($j == $monthto ? "selected" : "").'>'.$j.'</option>';
	}
?>
</select>월&nbsp;&nbsp;&nbsp;<select name="dayto[]">
<?php
	for ($j = 1 ; $j <= 31 ; $j++) {
		echo '<option value="'.$j.'"'.($j == $dayto ? "selected" : "").'>'.$j.'</option>';
	}
?>
</select>일&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<select name="hourto[]">
<?php
	for ($j = 0 ; $j <= 24 ; $j++) {
		echo '<option value="'.$j.'"'.($j == $hourto ? "selected" : "").'>'.$j.'</option>';
	}
?>
</select>시&nbsp;&nbsp;&nbsp;<select name="minuteto[]">
<?php
	for ($j = 0 ; $j <= 59 ; $j++) {
		echo '<option value="'.$j.'"'.($j == $minuteto ? "selected" : "").'>'.$j.'</option>';
	}
?>
</select>분</td>
<td style="width:100px; text-align:center;" class="td2"><a href="<? echo $relimgs[$i][4]; ?>">다운로드</a></td>
<td style="width:100px; text-align:center;" class="td2"><? echo ($curimage == $relimgs[$i][0]) ? "실행" : $relimgs[$i][5]; ?></td>
</tr>
<?php
}
?>
</table>
</form>

<!-- 페이지 목록 -->
<?php include 'pages.php'; ?>

<!-- 로딩 화면 -->
<div id="loadingview" style="position:absolute; left:0px; top:0px; width: 100%; height:100%; z-index:9999; background-color:#f1f1f1; visibility:hidden;">
<div style="position:absolute; width: 100%; top:50%; text-align:center;">업데이트 이미지 생성중...</div>
</div>

</body>
</html>

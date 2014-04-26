<?PHP
header("Content-Type:text/html; charset=UTF-8"); 
header("Cache-Control:no-cache");
header("Pragma:no-cache");
session_start();

$arrCtx = array("shinfsrtattr"=>"seqno",
				"shinfsrtodr"=>"asc");

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

$dftLang = 1;	// 한국어
$maxitem = 10;
$nidshopinfo = 0;
$infos = array();
$offset = ($curpage - 1) * $maxitem;
$nallitems = 0;
$maxpgno = 1;
$srtattr = array("seqno"=>"seqno", "title"=>"title", "location"=>"location", "postbegin"=>"postbegin", "postend"=>"postend");

if ($curpage <= 0) {
	$curpage = 1;
}

if ($ok) {
	
	$str = "SELECT count(*) as cnt FROM t_shoppinginfo;";
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
		$str = "SELECT i.idshopinfo, seqno, title, location, postbegin, postend, if(now() < postbegin, 0, if(now() <= postend, 1, 2)) as state FROM t_shoppinginfo as i inner join t_shoppinginfoext as a on (i.idshopinfo = a.idshopinfo and a.idlang = ".$dftLang
			.") order by ".$srtattr[$arrCtx["shinfsrtattr"]]." ".$arrCtx["shinfsrtodr"].", i.idshopinfo desc limit ".$offset.",".$maxitem.";";
		$nidshopinfo = $db->querySelect($str);
		
		for ($i = 0 ; $i < $nidshopinfo ; $i++) {
			$row = $db->goNext();
			$infos[$i] = array();
			$infos[$i][0] = $row['idshopinfo'];
			$infos[$i][1] = $row['seqno'];
			$infos[$i][2] = $row['title'];
			$infos[$i][3] = $row['location'];
			$infos[$i][4] = $row['postbegin'];
			$infos[$i][5] = $row['postend'];
			$infos[$i][6] = $row['state'];
		}
		$db->free();
	}

	$db->close();
}

?>


<html>
<head>
<title>
쇼핑정보관리
</title>
<link rel="stylesheet" type="text/css" href="./css/subpage.css" />
<link rel="stylesheet" type="text/css" href="./css/body.css" />
<script type="text/javascript" src="./js/update.js" ></script>
</head>
<body style="margin-left:0px; margin-top:0px; font-family: 돋움;">

<image src="./image/add.png" style="position:relative; left:0px; top:15px; width: 49px; height:32px; cursor:pointer;" alt="추가" onclick="regForm.action='subshoppinginfoaddform.php'; regForm.submit();"/>
<image src="./image/remove.png" style="position:relative; left:0px; top:15px; width: 49px; height:32px; cursor:pointer;" alt="삭제" onclick="if (testCheckbox('del[]')) { regForm.action='subshoppinginforemove.php'; regForm.submit(); }"/>
<image src="./image/change.png" style="position:relative; left:0px; top:15px; width: 49px; height:32px; cursor:pointer;" alt="수정" onclick="if (testCheckbox('del[]')) { regForm.action='subshoppinginfoupdateform.php'; regForm.submit(); }"/>
<image src="./image/sequpdate.png" style="position:relative; left:0px; top:15px; width: 70px; height:32px; cursor:pointer;" alt="순서수정" onclick="regForm.action='subshoppinginfosequpdate.php'; regForm.submit();"/>

<form id="regForm" name="regForm" method="post" action="">
<input type="hidden" name="curpage" value="<? echo $curpage; ?>" />
<table style="position:relative; left:0px; top:30px; width:945px;" class="tbl">
<tr style="height:42px;">
<th style="width:45px;" class="th1"><input type="checkbox" class="chkbox" onclick="toggleCheckbox(this.checked, 'del[]');"/></th>
<?
if ($arrCtx['shinfsrtattr'] == "seqno") {
	if ($arrCtx['shinfsrtodr'] == "asc") {
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
echo '<th style="width:100px; cursor:pointer;" class="th2" onclick="document.location = \'subshoppinginfoman.php?shinfsrtattr=seqno&shinfsrtodr='.$odr.'\';">순번'.$imghtml.'</th>';

if ($arrCtx['shinfsrtattr'] == "title") {
	if ($arrCtx['shinfsrtodr'] == "asc") {
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
echo '<th style="width:200px; cursor:pointer;" class="th2" onclick="document.location = \'subshoppinginfoman.php?shinfsrtattr=title&shinfsrtodr='.$odr.'\';">제목'.$imghtml.'</th>';

if ($arrCtx['shinfsrtattr'] == "location") {
	if ($arrCtx['shinfsrtodr'] == "asc") {
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
echo '<th style="width:200px; cursor:pointer;" class="th2" onclick="document.location = \'subshoppinginfoman.php?shinfsrtattr=location&shinfsrtodr='.$odr.'\';">장소'.$imghtml.'</th>';

if ($arrCtx['shinfsrtattr'] == "postbegin") {
	if ($arrCtx['shinfsrtodr'] == "asc") {
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
echo '<th style="width:200px; cursor:pointer;" class="th2" onclick="document.location = \'subshoppinginfoman.php?shinfsrtattr=postbegin&shinfsrtodr='.$odr.'\';">게시 시작'.$imghtml.'</th>';

if ($arrCtx['shinfsrtattr'] == "postend") {
	if ($arrCtx['shinfsrtodr'] == "asc") {
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
echo '<th style="width:200px; cursor:pointer;" class="th2" onclick="document.location = \'subshoppinginfoman.php?shinfsrtattr=postend&shinfsrtodr='.$odr.'\';">게시 종료'.$imghtml.'</th>';
?>
</tr>
<?php
$extcolor = array("#FF9900", "#000000", "#999999");

for ($i = 0 ; $i < $nidshopinfo ; $i++) {
?>
<tr style="height:42px;">
<td style="width:45px; text-align:center;" class="td1"><input type="checkbox" name="del[]" class="chkbox" value="<? echo $infos[$i][0]; ?>"/><input type="hidden" name="idshopinfo[]" value="<? echo $infos[$i][0]; ?>"/></td>
<td style="width:100px; text-align:center;" class="td2"><input type="text" name="seqno[]" maxlength="5" style="position:relative; top:5px; width: 95%; border-style:solid; border-width: 1px; border-color: #d9d9d9; text-align:center;" value="<? echo $infos[$i][1]; ?>"/></td>
<td style="width:200px; text-align:center;" class="td2"><? echo htmlspecialchars(stripslashes($infos[$i][2])); ?></td>
<td style="width:200px; text-align:center;" class="td2"><? echo htmlspecialchars(stripslashes($infos[$i][3])); ?></td>
<td style="width:200px; text-align:center; color:<? echo $extcolor[$infos[$i][6]]; ?>;" class="td2"><? echo $infos[$i][4]; ?></td>
<td style="width:200px; text-align:center; color:<? echo $extcolor[$infos[$i][6]]; ?>;" class="td2"><? echo $infos[$i][5]; ?></td>
</tr>
<?php
}
?>
</table>
</form>

<!-- 페이지 목록 -->
<?php include 'pages.php'; ?>

</body>
</html>

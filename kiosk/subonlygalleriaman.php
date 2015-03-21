<?PHP
header("Content-Type:text/html; charset=UTF-8"); 
header("Cache-Control:no-cache");
header("Pragma:no-cache");
session_start();

$arrCtx = array("ogsrtattr"=>"seqno",
				"ogsrtodr"=>"asc");

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
$maxitem = 11;
$niditems = 0;
$items = array();
$offset = ($curpage - 1) * $maxitem;
$nallitems = 0;
$maxpgno = 1;
$srtattr = array("seqno"=>"seqno");

if ($curpage <= 0) {
	$curpage = 1;
}

if ($ok) {
	$str = "SELECT idonlygalleria FROM t_onlygalleria as i where idlang = ".$dftLang;
	$str .= " order by ".$srtattr[$arrCtx["ogsrtattr"]]." ".$arrCtx["ogsrtodr"].", idonlygalleria;";

	$iditems = $db->getSingleList($str, "idonlygalleria");
	$nallitems = count($iditems);

	$maxpgno = ceil($nallitems / $maxitem);
	if ($maxpgno <= 0) {
		$maxpgno = 1;
	}
	if ($curpage > $maxpgno) {
		$curpage = $maxpgno;
		$offset = ($curpage - 1) * $maxitem;
	}

	if ($nallitems > 0) {
		$niditems = ($offset + $maxitem > $nallitems) ? ($nallitems - $offset) : ($maxitem);
		for ($i = 0, $j = 0 ; $i < $niditems ; $i++) {
			$str = "SELECT idonlygalleria, seqno, paththumb, pathtextimg, pathimg FROM t_onlygalleria where idlang = ".$dftLang." and idonlygalleria = ".$iditems[$offset + $i].";";
			$m = $db->querySelect($str);
			if ($m == 1) {
				$row = $db->goNext();
				$items[$j] = array();
				$items[$j][0] = $row['idonlygalleria'];
				$items[$j][1] = $row['seqno'];
				$items[$j][2] = $row['paththumb'];
				$items[$j][3] = $row['pathtextimg'];
				$items[$j][4] = $row['pathimg'];
				$j++;
			}
			$db->free();
		}
		$niditems = count($items);
	}

	$db->close();
}

?>


<html>
<head>
<title>
Only Galleria 관리
</title>
<link rel="stylesheet" type="text/css" href="./css/subpage.css" />
<link rel="stylesheet" type="text/css" href="./css/body.css" />
<script type="text/javascript" src="./js/update.js" ></script>
</head>
<body style="margin-left:0px; margin-top:0px; font-family: 돋움;">

<form id="regForm" name="regForm" method="post" action="">

<image src="./image/add.png" style="position:relative; left:0px; top:15px; width: 49px; height:32px; cursor:pointer;" alt="추가" onclick="regForm.action='subonlygalleriaaddform.php'; regForm.submit();"/>
<image src="./image/remove.png" style="position:relative; left:0px; top:15px; width: 49px; height:32px; cursor:pointer;" alt="삭제" onclick="if (testCheckbox('del[]')) { regForm.action='subonlygalleriaremove.php'; regForm.submit(); }"/>
<image src="./image/change.png" style="position:relative; left:0px; top:15px; width: 49px; height:32px; cursor:pointer;" alt="수정" onclick="if (testCheckbox('del[]')) { regForm.action='subonlygalleriaupdateform.php'; regForm.submit(); }"/>


<input type="hidden" name="curpage" value="<? echo $curpage; ?>" />
<table style="position:relative; left:0px; top:30px; width:945px;" class="tbl">
<tr style="height:42px;">
<th style="width:45px;" class="th1"><input type="checkbox" class="chkbox" onclick="toggleCheckbox(this.checked, 'del[]');"/></th>
<?
if ($arrCtx['ogsrtattr'] == "seqno") {
	if ($arrCtx['ogsrtodr'] == "asc") {
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
echo '<th style="width:150px; cursor:pointer;" class="th2" onclick="document.location = \'subonlygalleriaman.php?ogsrtattr=seqno&ogsrtodr='.$odr.'\';">순번'.$imghtml.'</th>';
?>

<th style="width:250px;" class="th2">썸네일</th>
<th style="width:250px;" class="th2">텍스트</th>
<th style="width:250px;" class="th2">이미지</th>
</tr>
<?php
for ($i = 0 ; $i < $niditems ; $i++) {
?>
<tr style="height:42px;">
<td style="width:45px; text-align:center;" class="td1"><input type="checkbox" name="del[]" class="chkbox" value="<? echo $items[$i][0]; ?>"/><input type="hidden" name="iditem[]" value="<? echo $items[$i][0]; ?>"/></td>
<td style="width:150px; text-align:center;" class="td2"><? echo $items[$i][1]; ?></td>
<td style="width:250px; text-align:center;" class="td2"><?
		if (strlen($items[$i][2]) > 0) {
?>
			<a href="<? echo $items[$i][2]; ?>" target="new"><img style="position:relative; top:0px; height:38px;" src="<? echo $items[$i][2]; ?>" alt=""/></a>
<?
		}
?>
</td>
<td style="width:250px; text-align:center;" class="td2"><?
		if (strlen($items[$i][3]) > 0) {
?>
			<a href="<? echo $items[$i][3]; ?>" target="new"><img style="position:relative; top:0px; height:38px;" src="<? echo $items[$i][3]; ?>" alt=""/></a>
<?
		}
?>
</td>
<td style="width:250px; text-align:center;" class="td2"><?
		if (strlen($items[$i][4]) > 0) {
?>
			<a href="<? echo $items[$i][4]; ?>" target="new"><img style="position:relative; top:0px; height:38px;" src="<? echo $items[$i][4]; ?>" alt=""/></a>
<?
		}
?>
</td>
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

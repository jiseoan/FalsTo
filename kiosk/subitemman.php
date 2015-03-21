<?PHP
header("Content-Type:text/html; charset=UTF-8"); 
header("Cache-Control:no-cache");
header("Pragma:no-cache");
session_start();

$arrCtx = array("br_kwrd"=>"",
				"brsrtattr"=>"category",
				"brsrtodr"=>"asc");

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
$idgrpcateg = 1;	// 브랜드분류
$maxitem = 10;
$niditems = 0;
$items = array();
$offset = ($curpage - 1) * $maxitem;
$nallitems = 0;
$maxpgno = 1;
$srtattr = array("category"=>"g.name", "name"=>"attrval", "hall"=>"hall", "floor"=>"floor", "phone"=>"phone");

if ($curpage <= 0) {
	$curpage = 1;
}

if ($ok) {
	$str = "SELECT distinct i.iditem FROM t_item as i inner join t_grp as g on (g.idgrpcateg = ".$idgrpcateg." and i.idgrp = g.idgrp and g.idlang = ".$dftLang.") inner join t_itemext as a on (i.iditem = a.iditem and idattr = 'name')";
	if (strlen($arrCtx["br_kwrd"]) > 0) {
		$str .= " where attrval like '%".$arrCtx["br_kwrd"]."%' or tag like '%".$arrCtx["br_kwrd"]."%' or phone like '%".$arrCtx["br_kwrd"]."%' or hall = '".$arrCtx["br_kwrd"]."' or floor = '".$arrCtx["br_kwrd"]."'";
	}
	$str .= " order by ".$srtattr[$arrCtx["brsrtattr"]]." ".$arrCtx["brsrtodr"].", i.iditem desc;";

	$iditems = $db->getSingleList($str, "iditem");
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
			$str = "SELECT i.iditem, ifnull(g.name,'') as category, a.attrval as name, ifnull(hall,'') as hall, ifnull(floor,'') as floor, ifnull(phone,'') as phone FROM t_item as i"
				." left join t_grp as g on (i.idgrp = g.idgrp and g.idlang = ".$dftLang.")"
				." inner join t_itemext as a on (i.iditem = a.iditem and idattr = 'name' and a.idlang = ".$dftLang.") where i.iditem = ".$iditems[$offset + $i].";";
			$m = $db->querySelect($str);
			if ($m == 1) {
				$row = $db->goNext();
				$items[$j] = array();
				$items[$j][0] = $row['iditem'];
				$items[$j][1] = $row['category'];
				$items[$j][2] = $row['name'];
				$items[$j][3] = $row['hall'];
				$items[$j][4] = $row['floor'];
				$items[$j][5] = $row['phone'];
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
브랜드관리
</title>
<link rel="stylesheet" type="text/css" href="./css/subpage.css" />
<link rel="stylesheet" type="text/css" href="./css/body.css" />
<script type="text/javascript" src="./js/update.js" ></script>
</head>
<body style="margin-left:0px; margin-top:0px; font-family: 돋움;">

<form id="regForm" name="regForm" method="post" action="">

<image src="./image/add.png" style="position:relative; left:0px; top:15px; width: 49px; height:32px; cursor:pointer;" alt="추가" onclick="regForm.action='subitemaddform.php'; regForm.submit();"/>
<image src="./image/remove.png" style="position:relative; left:0px; top:15px; width: 49px; height:32px; cursor:pointer;" alt="삭제" onclick="if (testCheckbox('del[]')) { regForm.action='subitemremove.php'; regForm.submit(); }"/>
<image src="./image/change.png" style="position:relative; left:0px; top:15px; width: 49px; height:32px; cursor:pointer;" alt="수정" onclick="if (testCheckbox('del[]')) { regForm.action='subitemupdateform.php'; regForm.submit(); }"/>

<!-- 검색 -->
<input type="text" name="br_kwrd" maxlength="50" style="position:relative; left:578px; top:5px; width: 150px; border-style:solid; border-width: 1px; border-color: #d9d9d9; text-align:center;" value="<? echo $arrCtx["br_kwrd"]; ?>"/>
<image src="./image/search.png" style="position:relative; left:578px; top:15px; width: 49px; height:32px; cursor:pointer;" alt="검색" onclick="regForm.action='subitemman.php'; regForm.submit();"/>


<input type="hidden" name="curpage" value="<? echo $curpage; ?>" />
<table style="position:relative; left:0px; top:30px; width:945px;" class="tbl">
<tr style="height:42px;">
<th style="width:45px;" class="th1"><input type="checkbox" class="chkbox" onclick="toggleCheckbox(this.checked, 'del[]');"/></th>
<th style="width:100px;" class="th2">순번</th>
<?
if ($arrCtx['brsrtattr'] == "category") {
	if ($arrCtx['brsrtodr'] == "asc") {
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
echo '<th style="width:250px; cursor:pointer;" class="th2" onclick="document.location = \'subitemman.php?brsrtattr=category&brsrtodr='.$odr.'\';">카테고리'.$imghtml.'</th>';

if ($arrCtx['brsrtattr'] == "name") {
	if ($arrCtx['brsrtodr'] == "asc") {
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
echo '<th style="width:250px; cursor:pointer;" class="th2" onclick="document.location = \'subitemman.php?brsrtattr=name&brsrtodr='.$odr.'\';">이름'.$imghtml.'</th>';

if ($arrCtx['brsrtattr'] == "hall") {
	if ($arrCtx['brsrtodr'] == "asc") {
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
echo '<th style="width:100px; cursor:pointer;" class="th2" onclick="document.location = \'subitemman.php?brsrtattr=hall&brsrtodr='.$odr.'\';">Building'.$imghtml.'</th>';

if ($arrCtx['brsrtattr'] == "floor") {
	if ($arrCtx['brsrtodr'] == "asc") {
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
echo '<th style="width:100px; cursor:pointer;" class="th2" onclick="document.location = \'subitemman.php?brsrtattr=floor&brsrtodr='.$odr.'\';">층'.$imghtml.'</th>';

if ($arrCtx['brsrtattr'] == "phone") {
	if ($arrCtx['brsrtodr'] == "asc") {
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
echo '<th style="width:100px; cursor:pointer;" class="th2" onclick="document.location = \'subitemman.php?brsrtattr=phone&brsrtodr='.$odr.'\';">전화번호'.$imghtml.'</th>';
?>
</tr>
<?php
for ($i = 0 ; $i < $niditems ; $i++) {
?>
<tr style="height:42px;">
<td style="width:45px; text-align:center;" class="td1"><input type="checkbox" name="del[]" class="chkbox" value="<? echo $items[$i][0]; ?>"/><input type="hidden" name="iditem[]" value="<? echo $items[$i][0]; ?>"/></td>
<td style="width:100px; text-align:center;" class="td2"><? echo ($offset + $i + 1); ?></td>
<td style="width:250px; text-align:center;" class="td2"><? echo $items[$i][1]; ?></td>
<td style="width:250px; text-align:center;" class="td2"><? echo $items[$i][2]; ?></td>
<td style="width:100px; text-align:center;" class="td2"><? echo $items[$i][3]; ?></td>
<td style="width:100px; text-align:center;" class="td2"><? echo $items[$i][4]; ?></td>
<td style="width:100px; text-align:center;" class="td2"><? echo $items[$i][5]; ?></td>
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

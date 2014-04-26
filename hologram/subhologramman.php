<?PHP
header("Content-Type:text/html; charset=UTF-8"); 
header("Cache-Control:no-cache");
header("Pragma:no-cache");
session_start();

$langRes = $_SESSION['langRes'][basename(__FILE__, ".php")];
$manLangCode = $_SESSION['manLangCode'];

$arrCtx = array("clisrtattr"=>"name",
				"clisrtodr"=>"asc");

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
$clients = array();
$nclients = 0;
$offset = ($curpage - 1) * $maxitem;
$nallitems = 0;
$srtattr = array("name"=>"name", "location"=>"position", "ip"=>"cliip4", "mac"=>"macaddr", "state"=>"state");

if ($curpage <= 0) {
	$curpage = 1;
}

if ($ok) {
	$str = "SELECT count(*) as cnt FROM t_client;";
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
		$str = "SELECT idclient, macaddr, cliip4, name, position, if(state = 'A' and addtime(alivetime, '0:0:45') >= now(), 2, if(state = 'A' and addtime(dalogtime, '0:0:45') >= now(), 1, 0)) as state FROM t_client order by ".$srtattr[$arrCtx["clisrtattr"]]." ".$arrCtx["clisrtodr"]." limit ".$offset.",".$maxitem.";";
		$nclients = $db->querySelect($str);

		for ($i = 0 ; $i < $nclients ; $i++) {
			$row = $db->goNext();;
			$clients[$i] = array();
			$clients[$i][0] = $row['idclient'];
			$clients[$i][1] = $row['name'];
			$clients[$i][2] = $row['position'];
			$clients[$i][3] = $row['cliip4'];
			$clients[$i][4] = $row['macaddr'];
			$clients[$i][5] = $row['state'];
		}

		$db->free();
	}

	$db->close();
}

?>



<html>
<head>
<link rel="stylesheet" type="text/css" href="./css/subpage.css" />
<script type="text/javascript" src="./js/update.js" ></script>
<script type="text/javascript" src="./js/selectsync.js" ></script>
</head>
<body style="margin-left:0px; margin-top:0px; font-family: 돋움;">

<image src="./image/<? echo $manLangCode ?>/change.png" style="position:relative; left:0px; top:15px; width: 49px; height:32px; cursor:pointer;" alt="수정" onclick="regForm.action='subhologramupdate.php'; regForm.submit();"/>
<image src="./image/<? echo $manLangCode ?>/remove.png" style="position:relative; left:0px; top:15px; width: 49px; height:32px; cursor:pointer;" alt="삭제" onclick="if (testCheckbox('del[]')) { regForm.action='subhologramremove.php'; regForm.submit(); }"/>
<image src="./image/<? echo $manLangCode ?>/poweron.png" style="position:relative; left:0px; top:15px; width: 70px; height:32px; cursor:pointer;" alt="전원켜기" onclick="if (testCheckbox('del[]')) { regForm.action='subhologrampoweron.php'; regForm.submit(); }"/>
<image src="./image/<? echo $manLangCode ?>/reboot.png" style="position:relative; left:0px; top:15px; width: 70px; height:32px; cursor:pointer;" alt="다시시작" onclick="if (testCheckbox('del[]')) { regForm.action='subhologramrebooting.php'; regForm.submit(); }"/>

<form id="regForm" name="regForm" method="post" action="">
<input type="hidden" name="curpage" value="<? echo $curpage; ?>" />
<table style="position:relative; left:0px; top:30px; width:945px;" class="tbl">
<tr style="height:42px;">
<th style="width:45px;" class="th1"><input type="checkbox" class="chkbox" onclick="toggleCheckbox(this.checked, 'del[]');"/></th>
<?
if ($arrCtx['clisrtattr'] == "name") {
	if ($arrCtx['clisrtodr'] == "asc") {
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
echo '<th style="width:200px; cursor:pointer;" class="th2" onclick="document.location = \'subhologramman.php?clisrtattr=name&clisrtodr='.$odr.'\';">'.$langRes["label"][0][$manLangCode].$imghtml.'</th>';

if ($arrCtx['clisrtattr'] == "location") {
	if ($arrCtx['clisrtodr'] == "asc") {
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
echo '<th style="width:200px; cursor:pointer;" class="th2" onclick="document.location = \'subhologramman.php?clisrtattr=location&clisrtodr='.$odr.'\';">'.$langRes["label"][1][$manLangCode].$imghtml.'</th>';

if ($arrCtx['clisrtattr'] == "ip") {
	if ($arrCtx['clisrtodr'] == "asc") {
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
echo '<th style="width:200px; cursor:pointer;" class="th2" onclick="document.location = \'subhologramman.php?clisrtattr=ip&clisrtodr='.$odr.'\';">'.$langRes["label"][2][$manLangCode].$imghtml.'</th>';

if ($arrCtx['clisrtattr'] == "mac") {
	if ($arrCtx['clisrtodr'] == "asc") {
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
echo '<th style="width:200px; cursor:pointer;" class="th2" onclick="document.location = \'subhologramman.php?clisrtattr=mac&clisrtodr='.$odr.'\';">'.$langRes["label"][3][$manLangCode].$imghtml.'</th>';
/*
echo '<th style="width:100px;" class="th2">PREVIEW</th>';
*/
if ($arrCtx['clisrtattr'] == "state") {
	if ($arrCtx['clisrtodr'] == "asc") {
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
echo '<th style="width:100px; cursor:pointer;" class="th2" onclick="document.location = \'subhologramman.php?clisrtattr=state&clisrtodr='.$odr.'\';">'.$langRes["label"][4][$manLangCode].$imghtml.'</th>';
?>
</tr>
<?php
$stateimgs = array("onoff01.png", "onoff02.png", "onoff03.png");

for ($i = 0 ; $i < $nclients ; $i++) {
?>
<tr style="height:42px;">
<td style="width:45px; text-align:center;" class="td1"><input type="checkbox" name="del[]" class="chkbox" value="<? echo $clients[$i][0]; ?>"/><input type="hidden" name="idclient[]" value="<? echo $clients[$i][0]; ?>"/></td>
<td style="width:200px; text-align:center;" class="td2"><input type="text" name="cliname[]"  maxlength="60" style="width: 95%; border-style:none; text-align:center;" value="<? echo $clients[$i][1]; ?>"/></td>
<td style="width:200px; text-align:center;" class="td2"><input type="text" name="clipos[]"  maxlength="60" style="width: 95%; border-style:none; text-align:center;" value="<? echo $clients[$i][2]; ?>"/></td>
<td style="width:200px; text-align:center;" class="td2"><? echo $clients[$i][3]; ?></td>
<td style="width:200px; text-align:center;" class="td2"><? echo $clients[$i][4]; ?></td>
<!--
<td style="width:100px; text-align:center;" class="td2"><image src="./image/preview.png" style=" cursor:pointer;" onclick="previewOpen('<? echo $clients[$i][1]; ?>');" onmousedown="this.src = './image/preview_press.png'" onmouseup="this.src = './image/preview.png'" onmouseout="this.src = './image/preview.png'"/></td>
-->
<td style="width:100px; text-align:center;" class="td2"><image src="./image/<? echo $stateimgs[$clients[$i][5]]; ?>"/></td>
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

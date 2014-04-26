<?PHP
header("Content-Type:text/html; charset=UTF-8"); 
header("Cache-Control:no-cache");
header("Pragma:no-cache");
session_start();

$arrCtx = array("logsrtattr"=>"date",
				"logsrtodr"=>"desc");

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
$logs = array();
$nlogs = 0;
$offset = ($curpage - 1) * $maxitem;
$nallitems = 0;
$logtitle = array(array("PC 종료실행", "설정등록", "서버변경"),
				  array("Agent 시작", "Agent 종료"),
				  array("App 시작", "App 종료", "App ID 변경", "App 프로세스 미존재", "App 다운로드"),
				  array("데이터 다운로드", "데이터 업데이트", "랭킹데이터 업데이트"));
$srtattr = array("name"=>"name", "ip"=>"l.cliip4", "mac"=>"macaddr", "date"=>"regdate", "desc"=>"major");

if ($curpage <= 0) {
	$curpage = 1;
}

if ($ok) {
	$str = "SELECT count(*) as cnt FROM t_clientlog;";
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
		$str = "SELECT l.idclilog, name, l.cliip4, macaddr, major, minor, ifnull(more, '') as more, regdate FROM t_clientlog as l left join t_client as c on(l.idclient = c.idclient) order by ".$srtattr[$arrCtx["logsrtattr"]]." ".$arrCtx["logsrtodr"].", l.idclilog desc limit ".$offset.",".$maxitem.";";
		$nlogs = $db->querySelect($str);
		
		for ($i = 0 ; $i < $nlogs ; $i++) {
			$row = $db->goNext();;
			$logs[$i] = array();
			$logs[$i][0] = $row['idclilog'];
			$logs[$i][1] = $row['name'];
			$logs[$i][2] = $row['cliip4'];
			$logs[$i][3] = $row['macaddr'];
			$logs[$i][4] = $row['regdate'];

			$logstr = $logtitle[$row['major'] - 1][$row['minor'] - 1];
			$logs[$i][5] = $logstr;

			if (strlen($row['more']) > 0) {
				$logs[$i][5] .= " : ".$row['more'];
			}
		}

		$db->free();
	}

	$db->close();
}

?>



<html>
<head>
<title>
로그관리
</title>
<link rel="stylesheet" type="text/css" href="./css/subpage.css" />
<script type="text/javascript" src="./js/update.js" ></script>
</head>
<body style="margin-left:0px; margin-top:0px; font-family: 돋움;">

<image src="./image/remove.png" style="position:relative; left:0px; top:15px; width: 49px; height:32px; cursor:pointer;" alt="삭제" onclick="if (testCheckbox('del[]')) { regForm.action='sublogremove.php'; regForm.submit(); }"/>

<form id="regForm" name="regForm" method="post" action="">
<input type="hidden" name="curpage" value="<? echo $curpage; ?>" />
<table style="position:relative; left:0px; top:30px; width:945px;" class="tbl">
<tr style="height:42px;">
<th style="width:45px;" class="th1"><input type="checkbox" class="chkbox" onclick="toggleCheckbox(this.checked, 'del[]');"/></th>
<th style="width:80px;" class="th2">번호</th>
<?
if ($arrCtx['logsrtattr'] == "name") {
	if ($arrCtx['logsrtodr'] == "asc") {
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
echo '<th style="width:100px; cursor:pointer;" class="th2" onclick="document.location = \'sublogman.php?logsrtattr=name&logsrtodr='.$odr.'\';">이름(ID)'.$imghtml.'</th>';

if ($arrCtx['logsrtattr'] == "ip") {
	if ($arrCtx['logsrtodr'] == "asc") {
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
echo '<th style="width:100px; cursor:pointer;" class="th2" onclick="document.location = \'sublogman.php?logsrtattr=ip&logsrtodr='.$odr.'\';">IP'.$imghtml.'</th>';

if ($arrCtx['logsrtattr'] == "mac") {
	if ($arrCtx['logsrtodr'] == "asc") {
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
echo '<th style="width:120px; cursor:pointer;" class="th2" onclick="document.location = \'sublogman.php?logsrtattr=mac&logsrtodr='.$odr.'\';">MAC Address'.$imghtml.'</th>';

if ($arrCtx['logsrtattr'] == "date") {
	if ($arrCtx['logsrtodr'] == "asc") {
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
echo '<th style="width:120px; cursor:pointer;" class="th2" onclick="document.location = \'sublogman.php?logsrtattr=date&logsrtodr='.$odr.'\';">날짜'.$imghtml.'</th>';

if ($arrCtx['logsrtattr'] == "desc") {
	if ($arrCtx['logsrtodr'] == "asc") {
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
echo '<th style="width:380px; cursor:pointer;" class="th2" onclick="document.location = \'sublogman.php?logsrtattr=desc&logsrtodr='.$odr.'\';">내용'.$imghtml.'</th>';
?>
</tr>
<?php
for ($i = 0 ; $i < $nlogs ; $i++) {
?>
<tr style="height:42px;">
<td style="width:45px; text-align:center;" class="td1"><input type="checkbox" name="del[]" class="chkbox" value="<? echo $logs[$i][0]; ?>"/><input type="hidden" name="idlog[]" value="<? echo $logs[$i][0]; ?>"/></td>
<td style="width:80px; text-align:center;" class="td2"><? echo $logs[$i][0]; ?></td>
<td style="width:100px; text-align:center;" class="td2"><? echo $logs[$i][1]; ?></td>
<td style="width:100px; text-align:center;" class="td2"><? echo $logs[$i][2]; ?></td>
<td style="width:120px; text-align:center;" class="td2"><? echo $logs[$i][3]; ?></td>
<td style="width:120px; text-align:center;" class="td2"><? echo $logs[$i][4]; ?></td>
<td style="width:380px; text-align:left; padding-left:5px;" class="td2"><? echo $logs[$i][5]; ?></td>
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

<?PHP
header("Content-Type:text/html; charset=UTF-8"); 
header("Cache-Control:no-cache");
header("Pragma:no-cache");
session_start();

$langRes = $_SESSION['langRes'][basename(__FILE__, ".php")];
$manLangCode = $_SESSION['manLangCode'];

?>
<?php include './common/db.php'; ?>
<?php

$ok = $db->open();

$idoperating = 0;
$hourbegin = '00:00:00';
$hourend = '23:59:59';

if ($ok) {
	$str = "SELECT count(*) as cnt FROM t_operating where enable = 'Y';";
	if ($db->queryCount($str, 'cnt') == 0) {
		$str = "insert into t_operating (hourbegin) values ('".$hourbegin."');";
		$db->query($str);
	}

	$str = "SELECT idoperating, hourbegin, hourend FROM t_operating where enable = 'Y' order by idoperating desc limit 0, 1;";
	$n = $db->querySelect($str);
	if ($n == 1) {
		$row = $db->goNext();;
		$idoperating = $row['idoperating'];
		$hourbegin = $row['hourbegin'];
		$hourend = $row['hourend'];
	}

	$db->free();

	$db->close();
}

date_default_timezone_set('Asia/Seoul');
$applydate = strtotime($hourbegin);
$hourfrom = date("H", $applydate);
$minutefrom = date("i", $applydate);

$applydate = strtotime($hourend);
$hourto = date("H", $applydate);
$minuteto = date("i", $applydate);

?>



<html>
<head>
<link rel="stylesheet" type="text/css" href="./css/subpage.css" />
<script type="text/javascript" src="./js/update.js" ></script>
</head>
<body style="margin-left:0px; margin-top:0px; font-family: 돋움;">

<form id="regForm" name="regForm" method="post" action="suboperatingtimeupdate.php">
<input type="hidden" name="idoperating" value="<? echo $idoperating; ?>"/>
<div style="position:relative; left:0px; top:0px; width:945px; height:52px; font-size: 14px;">
<select name="fromhour">
<?php
for ($i = 0 ; $i <= 24 ; $i++) {
	echo '<option value="'.$i.'"'.($i == $hourfrom ? "selected" : "").'>'.$i.'</option>';
}
?>
</select><? echo $langRes["label"][0][$manLangCode] ?>&nbsp;<select name="fromminute">
<?php
for ($i = 0 ; $i <= 59 ; $i++) {
	echo '<option value="'.$i.'"'.($i == $minutefrom ? "selected" : "").'>'.$i.'</option>';
}
?>
</select><? echo $langRes["label"][1][$manLangCode] ?>&nbsp;&nbsp;~&nbsp;&nbsp;<select name="tohour">
<?php
for ($i = 0 ; $i <= 24 ; $i++) {
	echo '<option value="'.$i.'"'.($i == $hourto ? "selected" : "").'>'.$i.'</option>';
}
?>
</select><? echo $langRes["label"][2][$manLangCode] ?>&nbsp;<select name="tominute">
<?php
for ($i = 0 ; $i <= 59 ; $i++) {
	echo '<option value="'.$i.'"'.($i == $minuteto ? "selected" : "").'>'.$i.'</option>';
}
?>
</select><? echo $langRes["label"][3][$manLangCode] ?>
<image src="./image/<? echo $manLangCode ?>/change.png" style="position:relative; left:30px; top:10px; width: 49px; height:32px; cursor:pointer;" alt="수정" onclick="regForm.submit();"/>
</div>
</form>


</body>
</html>

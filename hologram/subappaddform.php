<?PHP
header("Content-Type:text/html; charset=UTF-8"); 
header("Cache-Control:no-cache");
header("Pragma:no-cache");
session_start();

$langRes = $_SESSION['langRes'][basename(__FILE__, ".php")];
$manLangCode = $_SESSION['manLangCode'];
?>


<html>
<head>
<link rel="stylesheet" type="text/css" href="./css/subpage.css" />
<link rel="stylesheet" type="text/css" href="./css/body.css" />
<script type="text/javascript" src="./js/update.js" ></script>
<script type="text/javascript" src="./js/selectsync.js" ></script>
</head>
<body style="margin-left:0px; margin-top:0px; font-family: 돋움;">

<image src="./image/<? echo $manLangCode ?>/add.png" style="position:relative; left:0px; top:15px; width: 49px; height:32px; cursor:pointer;" alt="추가" onclick="if (addForm.file.value.length > 0) {loadingview.style.visibility = 'visible'; addForm.submit();} else addForm.file.focus();"/>
<image src="./image/<? echo $manLangCode ?>/cancel.png" style="position:relative; left:0px; top:15px; width: 49px; height:32px; cursor:pointer;" alt="취소" onclick="document.location.replace('subappman.php');"/>

<form id="addForm" name="addForm" enctype="multipart/form-data" method="post" action="subappadd.php">
<table style="position:relative; left:0px; top:30px; width:945px;" class="tbl">
<tr style="height:42px;">
<th style="width:145px;" class="th1"><? echo $langRes["label"][0][$manLangCode] ?></th>
<th style="width:400px;" class="th2"><? echo $langRes["label"][1][$manLangCode] ?></th>
<th style="width:400px;" class="th2"><? echo $langRes["label"][2][$manLangCode] ?></th>
</tr>
<tr style="height:42px;">
<?php
date_default_timezone_set('Asia/Seoul');
$applydate = strtotime ("+1 days");
$yearto = date("Y", $applydate);
$monthto = date("m", $applydate);
$dayto = date("d", $applydate);
$hourto = date("H", $applydate);
$minuteto = date("i", $applydate);

$yearbgn = $yearto;
?>
<td style="width:145px; text-align:center;" class="td2"><input type="text" name="riname"  maxlength="60" style="width: 100%; border-style:none; text-align:center;" value="<? echo $langRes["label"][3][$manLangCode] ?>"/></td>
<td style="width:400px; text-align:center;" class="td2"><select name="yearto">
<?
for ($k = 0, $year2 = $yearbgn ; $k < 10 ; $k++, $year2++) {
	echo '<option value="'.$year2.'" '.($year2 == $yearto ? "selected" : "").'>'.$year2.'</option>';
}
?>
</select><? echo $langRes["label"][4][$manLangCode] ?>&nbsp;&nbsp;&nbsp;<select name="monthto">
<?php
	for ($j = 1 ; $j <= 12 ; $j++) {
		echo '<option value="'.$j.'"'.($j == $monthto ? "selected" : "").'>'.$j.'</option>';
	}
?>
</select><? echo $langRes["label"][5][$manLangCode] ?>&nbsp;&nbsp;&nbsp;<select name="dayto">
<?php
	for ($j = 1 ; $j <= 31 ; $j++) {
		echo '<option value="'.$j.'"'.($j == $dayto ? "selected" : "").'>'.$j.'</option>';
	}
?>
</select><? echo $langRes["label"][6][$manLangCode] ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<select name="hourto">
<?php
	for ($j = 0 ; $j <= 24 ; $j++) {
		echo '<option value="'.$j.'"'.($j == $hourto ? "selected" : "").'>'.$j.'</option>';
	}
?>
</select><? echo $langRes["label"][7][$manLangCode] ?>&nbsp;&nbsp;&nbsp;<select name="minuteto">
<?php
	for ($j = 0 ; $j <= 59 ; $j++) {
		echo '<option value="'.$j.'"'.($j == $minuteto ? "selected" : "").'>'.$j.'</option>';
	}
?>
</select><? echo $langRes["label"][8][$manLangCode] ?></td>
<td style="width:400px; text-align:center;" class="td2"><input type="file" name="file" style="width:390px; border-style:none;"/></td>
</tr>
</table>
</form>
</div>

<!-- 로딩 화면 -->
<div id="loadingview" style="position:absolute; left:0px; top:0px; width: 100%; height:100%; z-index:9999; background-color:#f1f1f1; visibility:hidden;">
<div style="position:absolute; width: 100%; top:50%; text-align:center;"><? echo $langRes["label"][9][$manLangCode] ?></div>
</div>

</body>
</html>
